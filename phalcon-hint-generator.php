<?php
/**
 * PhalconHintGenerator
 *
 * Generates PHP code hints from Phalcon Zephir source for IDEs
 *
 * @link http://phalconphp.com Phalcon PHP official site
 * @author Lajos Bencz <lazos@lazos.me>
 * @license MIT
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version 1.0.0
 *
 */

error_reporting(E_ERROR);

if($argc<3) {
	print(PHP_EOL."\33[31;1mUsage: \33[0mphalcon-hint-generator.php <\33[4mdirectory".DIRECTORY_SEPARATOR."cphalcon".DIRECTORY_SEPARATOR."phalcon".DIRECTORY_SEPARATOR."\33[0m> <\33[4mdirectory".DIRECTORY_SEPARATOR."output".DIRECTORY_SEPARATOR."\33[0m>\33[0m".PHP_EOL.PHP_EOL);
	exit(255);
}


$ph = new PhalconHintGenerator($argv[1],$argv[2]);
$ph->generate();


abstract class PhalconHintGenerator_Base {

	const DS = DIRECTORY_SEPARATOR;
	const EXT_ZEPHIR = 'zep';
	const CORE_TYPE_ZEPHIR_ONLY = 1;
	const CORE_TYPE_NO_PARAMETER = 2;
	const CORE_TYPE_ALLOW_PARAMETER = 3;

	protected static $CORE_TYPES = [
		'var' => self::CORE_TYPE_ZEPHIR_ONLY,
		'long' => self::CORE_TYPE_ZEPHIR_ONLY,
		'string' => self::CORE_TYPE_NO_PARAMETER,
		'int' => self::CORE_TYPE_NO_PARAMETER,
		'float' => self::CORE_TYPE_NO_PARAMETER,
		'double' => self::CORE_TYPE_NO_PARAMETER,
		'boolean' => self::CORE_TYPE_NO_PARAMETER,
		'null' => self::CORE_TYPE_NO_PARAMETER,
		'mixed' => self::CORE_TYPE_ALLOW_PARAMETER,
		'array' => self::CORE_TYPE_ALLOW_PARAMETER,
		'object' => self::CORE_TYPE_ALLOW_PARAMETER,
		'resource' => self::CORE_TYPE_ALLOW_PARAMETER,
		'callable' => self::CORE_TYPE_ALLOW_PARAMETER,
	];

	protected static $REMAP_TYPES = [
		'var' => '',
		'long' => 'int',
	];

	protected function log($message, $end=PHP_EOL, $colour=0) {
		echo "\33[0;", intval($colour), "m", $message, "\33[0m", ($end?:'');
	}

	protected function inf($message, $end=PHP_EOL) {
		$this->log($message, $end, 36);
	}

	protected function suc($message, $end=PHP_EOL) {
		$this->log($message, $end, 32);
	}

	protected function war($message, $end=PHP_EOL) {
		$this->log($message, $end, 33);
	}

	protected function exc(Exception $exception) {
		$this->log("");
		$this->log("\33[5m".$exception->getMessage(), PHP_EOL, 31);
		$this->log("");
		$this->log($exception->getTraceAsString());
		$this->log("");
	}

	abstract function generate();

}

class PhalconHintGenerator extends PhalconHintGenerator_Base {

	/** @var string */
	protected $dirSrc;
	/** @var string */
	protected $dirOut;
	/** @var PhalconHintGenerator_Source */
	protected $source;

	protected static function fixSlashes($path) {
		$sl = strlen($path);
		if($sl<1) {
			return self::DS;
		}
		if($path[0]!==self::DS) {
			$path = self::DS.$path;
			$sl++;
		}
		if($sl>2 && $path[$sl-1]===self::DS) {
			$path = substr($path,0,$sl-1);
		}
		return $path;
	}

	protected static function nsToPath($namespace) {
		return str_replace('\\',self::DS,$namespace);
	}

	public function __construct($dirSrc, $dirOut) {
		$this->dirSrc = realpath($dirSrc);
		$this->dirOut = realpath($dirOut);
		$this->source = new PhalconHintGenerator_Source();
	}

	public function generate() {
		if(!is_readable($this->dirSrc)) throw new Exception("Can't read source directory");
		if(!is_writable($this->dirOut)) throw new Exception("Can't write to output directory");
		$this->clearOutputDir();
		$this->getVersion();
		$this->directory('');
	}

	protected function getVersion() {
		$file = $this->dirSrc.self::DS.'version.zep';
		if(!is_readable($file)) return;
		$version = file_get_contents($file);
		if(preg_match('/protected\sstatic\sfunction\s\_getVersion\(\)\s\-\>\sarray.*?\{.*?return\s+\[\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d]+)\s*]\s*;/ms',$version,$m)) {
			$this->dirOut.= self::DS.$m[1].'.'.$m[2].'.'.$m[3].'.'.$m[4];
		}
	}

	protected function clearOutputDir() {
		if(!is_dir($this->dirOut)) {
			return;
		}
		$di = new RecursiveDirectoryIterator($this->dirOut, RecursiveDirectoryIterator::SKIP_DOTS);
		$fi = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
		foreach($fi as $i) {
			if($i->isDir()) {
				rmdir($i->getRealPath());
			} else {
				unlink($i->getRealPath());
			}
		}
	}

	protected function createOutputPath($path) {
		$path = self::fixSlashes($path);
		$real = $this->dirOut.$path;
		if(is_dir($real)) {
			return;
		}
		if(!mkdir($real,0777,true)) {
			throw new Exception("Failed to create output directory: {$real}");
		}
	}

	protected function directory($path) {
		$this->inf("Entering \33[0m{$path}");
		$realpath = $this->dirSrc.$path;
		if(!is_readable($realpath)) throw new Exception("Can't read directory: {$realpath}");
		$paths = [];
		$files = [];
		$ext = '.'.self::EXT_ZEPHIR;
		$di = new DirectoryIterator($realpath);
		foreach($di as $i) {
			if($i->isDot() || $i->isLink()) {
				continue;
			}
			if($i->isDir()) {
				$paths[] = $i->getBasename();
			}
			elseif($i->isFile() && $i->getExtension()===self::EXT_ZEPHIR) {
				$files[] = $i->getBasename($ext);
			}
		}
		asort($paths,SORT_NATURAL);
		asort($files,SORT_NATURAL);
		foreach ($files as $f) {
			$fz = $realpath.self::DS.$f.$ext;
			$this->inf("\33[0;5m".$fz);
			$this->file($fz);
		}
		foreach($paths as $p) {
			$this->directory($path.self::DS.$p);
		}

		$this->suc("Leaving \33[0m{$path}");
	}

	protected function file($zep) {
		$this->source->generate(file_get_contents($zep));
		$ns = $this->source->getNamespace();
		$name = $this->source->getClassName();
		$result = (string)$this->source;
		$outPath = self::nsToPath($ns);
		$this->createOutputPath($outPath);
		$outFile = $this->dirOut.self::DS.$outPath.self::DS.$name.'.php';
		file_put_contents($outFile,$result);
	}

}

class PhalconHintGenerator_Source extends PhalconHintGenerator_Base {

	protected $_source;
	protected $_clean;
	protected $_comments = [];
	protected $_namespace;
	protected $_namespaceOffset = 0;
	protected $_use = [];
	protected $_useAs = [];
	protected $_className;
	protected $_classNameOffset = 0;
	protected $_result;

	public function __construct($source=false) {
		if(is_string($source)) $this->setSource($source);
	}

	public function setSource($source) {
		$this->_source = $source;
		$this->_clean = '';
		$this->_comments = [];
		$this->_namespace = '';
		$this->_namespaceOffset = 0;
		$this->_use = [];
		$this->_useAs = [];
		$this->_className = '';
		$this->_classNameOffset = 0;
		$this->_result = '';
	}

	public function generate($source=false) {
		if(is_string($source)) {
			$this->setSource($source);
		}
		$this->stripComments();

		$fullNameSpace = $this->findNamespace();
		$fullClassName = $this->findClassName();

		$this->_result = '<?php'.PHP_EOL.PHP_EOL;
		$this->_result.= $fullNameSpace.PHP_EOL.PHP_EOL;

		// USE
		preg_match_all('/use\s+((?:\\\\?[\w\d_]+)+)(?:\s+as\s([\w\d_]+))?\s*;/ims',$this->_clean,$m,PREG_OFFSET_CAPTURE);
		foreach($m[0] as $mk=>$mv) {
			$classPath = trim($m[1][$mk][0]);
			$as = trim($m[2][$mk][0]);
			if(strlen($as)<1) {
				$parts = explode('\\',$classPath);
				$as = array_pop($parts);
				if($as==$this->_className) {
					continue;
				}
				foreach($this->_use as $k=>&$v) {
					if(strcasecmp($k,$as)===0) {
						continue 2;
					}
				}
				$this->_use[$as] = $classPath;
			} else {
				if(array_key_exists($as,$this->_useAs) || $as==$this->_className) {
					continue;
				}
				$this->_useAs[$as] = $classPath;
			}
			$this->_result.= trim($mv[0]).PHP_EOL;
		}
		if($m[0]) $this->_result.= ''.PHP_EOL.PHP_EOL;

		$this->_result.= $fullClassName.PHP_EOL.PHP_EOL;

		$commentFrom = $this->_classNameOffset;

		// CONSTANT
		preg_match_all('/\s+const\s+(?:[\w\d_]+)(?:\s*=\s*((\'.*?\')|(".*?")|([\w\d\_\:\s\\\\]+)))\s*;/ims',$this->_clean,$m,PREG_OFFSET_CAPTURE);
		foreach($m[0] as $mk=>$mv) {
			$s = trim($mv[0]);
			$p = intval($mv[1]);
			$l = strlen($s);
			$c = $this->getComment($commentFrom,$p);
			$this->_result.= $c."\t".$s.PHP_EOL.PHP_EOL;
			$commentFrom = $p+$l;
		}
		if($m[0]) $this->_result.= ''.PHP_EOL.PHP_EOL;

		// PROPERTY
		$inject_services = [];
		if($this->_className=='Injectable' && $this->_namespace=='Phalcon\Di' && is_file(__DIR__.self::DS.'inject_services.txt')) {
			$fh = fopen(__DIR__.self::DS.'inject_services.txt','r');
			if($fh) {
				while(($line = fgets($fh))) {
					$line = preg_replace('/[\t\s]+/',"\t",$line);
					$line = explode("\t",$line);
					if(count($line)>1) {
						$inject_services[trim($line[0])] = trim($line[1]);
					}
				}
				fclose($fh);
			}
		}
		preg_match_all('/((?:(?:public|protected|private|static))+)\s+([\w\d_]+)(?:\s+=(.*?))?(?:\s*{(\s*(?:[\w\d_]+\s*\,?\s*)+)})?;/ims',$this->_clean,$m,PREG_OFFSET_CAPTURE);
		foreach($m[0] as $mk=>$mv) {
			$type = trim($m[1][$mk][0]);
			$name = trim($m[2][$mk][0]);
			$default = trim($m[3][$mk][0]);
			$dl = strlen($default);
			if($dl>1 && $default[0]=='"' && $default[$dl-1]=='"') {
				$default[0] = $default[$dl-1] = "'";
			}
			$methods = trim($m[4][$mk][0]);
			$comment = $this->getComment($commentFrom,$m[0][$mk][1]);

			$this->_result.= $comment."\t".$type.' $'.$name.($default?' = '.$default:'').';'.PHP_EOL.PHP_EOL;
			if(strlen($methods)) {
				$methods = explode(',',$methods);
				foreach($methods as $me) {
					$me = trim($me);
					$mName = $name;
					if($mName[0]=='_') $mName = substr($mName,1);
					if($me=='set') $me = $me.ucfirst($mName).'($value) {'.PHP_EOL."\t\t".'$this->'.$name.' = $value;'.PHP_EOL."\t}".PHP_EOL;
					elseif($me=='get') $me = $me.ucfirst($mName)."() {".PHP_EOL."\t\t".'return $this->'.$name.';'.PHP_EOL."\t}".PHP_EOL;
					else $me = $me."() {".PHP_EOL."\t\t".'return $this->'.$name.";".PHP_EOL."\t}".PHP_EOL;
					$this->_result.= "\tpublic function ".$me.PHP_EOL;
				}
			}
			if(array_key_exists($name,$inject_services)) unset($inject_services[$name]);
			$commentFrom = $m[0][$mk][1] + strlen($m[0][$mk][0]);
		}
		if($m[0]) $this->_result.= ''.PHP_EOL.PHP_EOL;

		if(count($inject_services)>0) {
			foreach($inject_services as $name=>$type) {
				$line = "\t/**".PHP_EOL."\t * @var ".$type.PHP_EOL."\t */".PHP_EOL."\t".'public $'.$name.';'.PHP_EOL.PHP_EOL;
				$commentFrom+=strlen($line);
				$this->_result.=$line;
			}
			$this->_result.= ''.PHP_EOL.PHP_EOL;
		}

		// FUNCTION
		preg_match_all('/((?:(?:public|protected|private|final|static|abstract)\s+)+)function\s*\$?([\w\d_]+)\s*\((.*?)\)(?:\s*\-\>((?:\s*\|?\s*[\w\d_\<\>\\\\\[\]]+)+))?.*?({|;)/ims',$this->_clean,$m,PREG_OFFSET_CAPTURE);
		foreach($m[0] as $mk=>$mv) {
			$type = trim($m[1][$mk][0]);
			$name = trim($m[2][$mk][0]);
			$prms = trim($m[3][$mk][0]);
			$hint = trim($m[4][$mk][0]);
			$returns = false;

			if($m[5][$mk][0]!=='{') {
				$end = ';';
			} else {
				$end = ' {}';
				$s1 = false;
				$s2 = false;
				$bra = 1;
				$esc = 0;
				$i = $m[5][$mk][1];
				while($bra>0 && !$returns) {
					$i++;
					$c = $this->_clean[$i];
					$c1 = $this->_clean[$i-1];
					if($c=='\\') {
						if($c1=='\\') {
							$esc++;
						} else {
							$esc = 1;
						}
					} else {
						if($esc%2==0) {
							if(!$s2 && $c=="'") {
								$s1=!$s1;
							}
							elseif(!$s1 && $c=='"') {
								$s2=!$s2;
							}
							elseif(!$s1 && !$s2) {
								if($c=='{') $bra++;
								elseif($c=='}') $bra--;
								else {
									if(preg_match('/[\s\t\n]return[\s\t\n\;]/ims',substr($this->_clean,$i,8))) {
										$returns = true;
										break;
									}
								}
							}
						}
						$esc = 0;
					}
				}
			}

			$line = "\t".$type." function ".$name."(";

			$prmse = explode(',',$prms);
			$prms = [];
			$types = [];
			foreach($prmse as $prm) {
				$prm = trim($prm);
				$pType = $pName = $pDef = false;
				if(preg_match('/^<(?<type>[^>]+)>\s+(?<name>[\w\d_]+)\s*(?:\=\s*(?<def>.*))?$/ims', $prm, $mp)) {
					$pType = $mp['type'];
					$pName = $mp['name'];
					$pDef = $mp['def']?:false;
				}
				elseif(preg_match('/^(?<type>[\w\d_\[\]]+)(!?)\s+(?<name>[\w\d_]+)\s*(?:\=\s*(?<def>.*))?$/ims', $prm, $mp)) {
					$pType = $mp['type'];
					$pName = $mp['name'];
					$pDef = $mp['def']?:false;
				}
				elseif(preg_match('/^(?<name>[\w\d_]+)\s*(?:\=\s*(?<def>.*))?$/ims', $prm, $mp)) {
					$pType = $mp['type'];
					$pName = $mp['name'];
					$pDef = $mp['def']?:false;
				}
				if($pName) {
					$pTypeSafe = '';
					if(self::$CORE_TYPES[$pType]===self::CORE_TYPE_ALLOW_PARAMETER) $pTypeSafe = $pType;
					elseif(array_key_exists($pType,self::$REMAP_TYPES)) $pType = self::$REMAP_TYPES[$pType];
					elseif(!array_key_exists($pType,self::$CORE_TYPES)) $pTypeSafe = $pType;
					if($pTypeSafe[0]==='$') $pTypeSafe = substr($pTypeSafe,1);
					$p = trim($pTypeSafe.' $'.$pName);
					if($pDef) $p.= "=".trim($pDef);
					$prms[] = $p;
					$types[$pName] = trim($pType);
				}
			}

			$line.= implode(', ',$prms).")".$end;
			$comment = $this->getComment($commentFrom, $m[0][$mk][1], $types, trim($hint)?:($returns?'mixed':'void'));

			$this->_result.= $comment.$line.PHP_EOL.PHP_EOL;

			$commentFrom = $m[0][$mk][1] + strlen($m[0][$mk][0]);
		}

		$this->_result.= '}'.PHP_EOL;
	}

	public function getNamespace() {
		return $this->_namespace;
	}

	public function getClassName() {
		return $this->_className;
	}

	public function __toString() {
		return $this->_result;
	}

	protected function stripComments() {
		$clean = '';
		$s1 = false;
		$s2 = false;
		$c1 = false;
		$c2 = false;
		$cm1 = false;
		$len = strlen($this->_source);
		$skip = false;
		$skipNum = 0;
		$comment = '';
		$offset = false;
		$escape = 0;
		for($i=0; $i<$len; $i++) {
			$c = $this->_source[$i];
			$cp1 = $this->_source[$i+1]?:false;
			$cp2 = $this->_source[$i+2]?:false;
			if($c=='\\') {
				if($cm1=='\\') {
					$escape++;
				}
				else {
					$escape=1;
				}
			}
			else {
				if($escape%2==0) {
					if($c=="'" && !$s2 && !$c1 && !$c2) {
						$skip = false;
						$skipNum = 0;
						$s1 = !$s1;
						$offset = false;
					}
					elseif($c=='"' && !$s1 && !$c1 && !$c2) {
						$skip = false;
						$skipNum = 0;
						$s2 = !$s2;
						$offset = false;
					}
					elseif($c=='/') {
						if(!$s1 && !$s2 && !$c1 && !$c2 && $cp1=='/') {
							$skip = true;
							$skipNum = 0;
							$c1 = true;
							$offset = false;
						}
						elseif(!$s1 && !$s2 && !$c1 && !$c2 && $cp1=='*' && $cp2=='*') {
							$skip = true;
							$skipNum = 0;
							$c2 = true;
							$offset = $i;
						}
					}
					elseif($c=='*' && $c2 && $cp1=='/') {
						$skip = true;
						$skipNum = 2;
						$c2 = false;
					}
					elseif($c=="\n" && $c1) {
						$skip = false;
						$skipNum = 1;
						$c1 = false;
						$offset = false;
					}
				}
				$escape = 0;
			}
			if($skipNum==0 && !$c1 && !$c2) {
				$skip = false;
			}
			if(!$skip || $c=="\r" || $c=="\n") {
				$clean.=$c;
			}
			else {
				$clean.=" ";
			}
			if(!$skip) {
				if(strlen($comment)>0 && $offset!==false) {
					$this->_comments[] = [
						'offset' => $offset,
						'length' => strlen($comment),
						'comment' => $comment,
					];
					$comment = '';
					$offset = false;
				}
			}
			elseif($offset!==false) {
				$comment.=$c;
			}
			if($skipNum>0) $skipNum--;
			if($i>0) $cm1 = $c;
		}
		$this->_clean = $clean;
		if(strlen($this->_clean)!=strlen($this->_source)) {
			throw new Exception("Failed to consistently strip comments!");
		}
	}

	protected function getComment($from,$to,$types=[],$return='') {
		$comment = false;
		$hasTypes = is_array($types) && count($types)>0;
		$hasReturn = strlen(trim($return))>0;
		foreach($this->_comments as $c) {
			$s = intval($c['offset']);
			$e = $s+intval($c['length']);
			if($s>=$from && $e<=$to) {
				$comment = trim($c['comment']);
				break;
			}
		}
		if(!$comment) {
			if(!$hasTypes && !$hasReturn) {
				return '';
			}
			$comment = "/**".PHP_EOL."\t */";
		}
		if($hasTypes) {
			preg_match_all('/\@param\s+([\w\d_\|\s\\\\\[\]]+)\s+([\w\d_]+)/i',$comment,$m);
			foreach ($m[0] as $mk => $mv) {
				$mName = $m[2][$mk];
				$mType = $m[1][$mk];
				$types[$mName] = $types[$mName]?:$mType;
			}
			$comment = preg_replace('/^\s*\*?\s*@param\s+(.*?)\r?\n/im','',$comment);
			$params = '';
			foreach($types as $name=>$type) {
				$type = $this->expandUseAs($type);
				$params.= PHP_EOL."\t * @param ".($type?$type.' ':'').'$'.$name;
			}
			$comment = preg_replace_callback('/\s*\r?\n\s*(\*\s*)?(\@return\s+.*?)?\*\//ims',function($m) use ($params) {
				return (!$m[2]?PHP_EOL."\t * ":'').$params.($m[2]?PHP_EOL."\t * ":'').$m[0];
			},$comment);
		}
		preg_match('/\@return\s+([\w\d_\|\s\\\\\[\]]+)/i',$comment,$m);
		$return = str_replace(['<','>'],'',trim($return));
		if($m[0]) {
			$return = $this->expandUseAs($return?:$m[1]);
			$n = '@return '.$return;
			$comment = str_replace($m[0],$n.PHP_EOL."\t ",$comment);
		}
		elseif($hasReturn) {
			$return = $this->expandUseAs($return);
			$r = '@return '.$return;
			$comment = str_replace('*/','*'.PHP_EOL,$comment);
			$comment.= "\t * ".$r.PHP_EOL;
			$comment.= "\t */";
		}
		$comment = preg_replace_callback('/\@(param|return)\s+var($|\s)/i',function($m){
			return '@'.$m[1].' mixed'.$m[2];
		},$comment);

		return "\t".$comment.PHP_EOL;
	}

	protected function findNamespace() {
		preg_match('/namespace\s+((?:\\\\?[\w\d_]+)+)\s*;/ims',$this->_clean,$m,PREG_OFFSET_CAPTURE);
		$this->_namespace = $m[1][0];
		$this->_namespaceOffset = $m[0][1];
		return trim($m[0][0]);
	}

	protected function findClassName() {
		preg_match('/(((?:(?:abstract|final)\s*)+)?(class|interface)\s*\$?(?<name>[\w\d\_]+)(?:\s+extends\s*([\\\\\w\d_]+))?(?:\s+implements\s+((?:\s*\,?\s*[\\\\\w\d_]+)+))?)(?:\s*{)/ims',$this->_clean,$m,PREG_OFFSET_CAPTURE);
		$this->_className = $m['name'][0];
		$this->_classNameOffset = $m[0][1];
		return trim(str_replace('$','',$m[0][0]));
	}

	protected function expandUseAs($type) {
		$type = trim($type);
		if(strlen($type)<1) {
			return '';
		}
		$types = explode('|',$type);
		$return = [];
		foreach($types as $type) {
			$type = $safeType = trim($type);
			$isArray = substr($type,-2,2) == '[]';
			if($isArray) {
				$safeType = substr($type,0,-2);
			}
			if($safeType==$this->_className) {
				$return[] = $type;
			}
			elseif (array_key_exists($safeType, self::$CORE_TYPES)) {
				if (self::$CORE_TYPES[$safeType] == self::CORE_TYPE_ZEPHIR_ONLY) {
					continue;
				}
				$return[] = $type;
			}
			elseif(array_key_exists($safeType, $this->_use) || array_key_exists($safeType, $this->_useAs)) {
				$return[] = $type;
			}
			elseif($safeType[0] !== '\\') {
				$return[] = '\\' . $type;
			}
			else {
				$return[] = $type;
			}
		}
		return implode('|',$return);
	}

}
