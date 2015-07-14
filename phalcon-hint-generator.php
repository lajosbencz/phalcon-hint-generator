<?php

/**
 * PhalconHintGenerator
 *
 * Generates PHP code hints from (Phalcon) Zephir source for IDEs
 *
 * @link http://phalconphp.com Phalcon PHP official site
 * @link http://zephir-lang.com/ Zephir language
 * @author Lajos Bencz <lazos@lazos.me>
 * @license MIT
 * @license http://opensource.org/licenses/MIT The MIT License
 *
 */

error_reporting(E_ERROR);

$params = [];
$silent = false;
$force = false;
foreach ($argv as $i => $v) {
	if ($i < 1) {
		continue;
	}
	if ($v == '--silent' || $v == '-s') {
		$silent = true;
		continue;
	}
	elseif ($v == '--force' || $v == '-f') {
		$force = true;
		continue;
	}
	$params[] = $v;
}

if (count($params) < 2) {
	PhalconHintGenerator_Message::U();
	exit(255);
}

$ph = new PhalconHintGenerator($params[0], $params[1]);
$ph->verbose(!$silent);
$ph->force($force);
try {
	$ph->process();
	exit(0);
}
catch (PhalconHintGenerator_AbortException $e) {
	$ph->err($e->getMessage());
}
catch (PhalconHintGenerator_Exception $e) {
	$ph->err($e->getMessage());
}

exit(255);

class PhalconHintGenerator_Exception extends Exception
{
	public function __construct($message = '', $code = 0, $previous = null)
	{
		$message = PHP_EOL . "\33[31m" . preg_replace('/\\33\[[\d\;]+m/', '', $message) . PHP_EOL;
		parent::__construct($message, $code, $previous);
	}
}

abstract class PhalconHintGenerator_Message
{
	const DS = DIRECTORY_SEPARATOR;

	const CLR_RESET = 0;
	const CLR_BOLD = 1;
	const CLR_DARK = 2;
	const CLR_ITALIC = 3;
	const CLR_UNDERLINE = 4;
	const CLR_BLINK = 5;
	const CLR_REVERSE = 7;
	const CLR_CONCEAL = 8;
	const CLR_BLACK = 30;
	const CLR_RED = 31;
	const CLR_GREEN = 32;
	const CLR_YELLOW = 33;
	const CLR_BLUE = 34;
	const CLR_MAGENTA = 35;
	const CLR_CYAN = 36;
	const CLR_L_GRAY = 37;
	const CLR_DEFAULT = 39;
	const CLR_B_BLACK = 40;
	const CLR_B_RED = 41;
	const CLR_B_GREEN = 42;
	const CLR_B_YELLOW = 43;
	const CLR_B_BLUE = 44;
	const CLR_B_MAGENTA = 45;
	const CLR_B_CYAN = 46;
	const CLR_B_L_GRAY = 47;
	const CLR_B_DEFAULT = 49;
	const CLR_D_GRAY = 90;
	const CLR_L_RED = 91;
	const CLR_L_GREEN = 92;
	const CLR_L_YELLOW = 93;
	const CLR_L_BLUE = 94;
	const CLR_L_MAGENTA = 95;
	const CLR_L_CYAN = 96;
	const CLR_WHITE = 97;
	const CLR_BD_GRAY = 100;
	const CLR_BL_RED = 101;
	const CLR_BL_GREEN = 102;
	const CLR_BL_YELLOW = 103;
	const CLR_BL_BLUE = 104;
	const CLR_BL_MAGENTA = 105;
	const CLR_BL_CYAN = 106;
	const CLR_B_WHITE = 107;

	/** @var array */
	private static $LOADING_CHARS = [
		'.     ',
		'..    ',
		'...   ',
		' ...  ',
		'  ... ',
		'   ...',
		'    ..',
		'     .',
	];
	/** @var float */
	private static $LOADING_WAIT = 0.2;

	/**
	 * Creates colour format
	 * @param int|int[] $colour
	 * @param int ...N
	 * @return string
	 */
	public static function C($colour = 0)
	{
		if (is_array($colour)) $colour = implode(';', $colour);
		else $colour = implode(';', func_get_args());
		return "\33[" . $colour . "m";
	}

	/**
	 * Prints usage info to STDOUT
	 * @return void
	 */
	public static function U()
	{
		echo PHP_EOL,
		self::C(self::CLR_RED),
		"Usage:",
		PHP_EOL,
		self::C(self::CLR_RESET),
		"php phalcon-hint-generator.php ",
			"<" . self::C(self::CLR_UNDERLINE) . "directory" . self::DS . "cphalcon" . self::DS . "phalcon" . self::DS . self::C(self::CLR_RESET) . "> ",
			"<" . self::C(self::CLR_UNDERLINE) . "directory" . self::DS . "output" . self::DS . self::C(self::CLR_RESET) . "> ",
		self::C(self::CLR_RESET),
			PHP_EOL . PHP_EOL;
	}

	protected $_verbose = false;
	protected $_lastWasLine = false;
	protected $_lastWasLoading = false;
	protected $_lastWasProgress = false;
	protected $_loadingChar = 0;
	protected $_loadingTime = false;

	/**
	 * Read string from STDIN
	 * @return string
	 * @throws Exception
	 */
	public function read()
	{
		static $fh;
		if (!$fh) $fh = @fopen('php://stdin', 'r');
		if (!$fh) throw new Exception("Failed to open STDIN!");
		$in = trim(fgets($fh));
		return $in;
	}

	/**
	 * Confirm action from STDIN
	 * @param string $message
	 * @return bool
	 * @throws Exception
	 */
	public function confirm($message = 'Please confirm!')
	{
		$v = $this->_verbose;
		$this->_verbose = true;
		$this->war($message . self::C(self::CLR_RESET) . ' (y|yes)');
		$this->_verbose = $v;
		$in = strtolower($this->read());
		return $in == 'y' || $in == 'yes';
	}

	/**
	 * Set verbose on/off
	 * @param bool $verbose
	 */
	public function verbose($verbose = true)
	{
		$this->_verbose = $verbose;
	}

	/**
	 * Should messages be printed
	 * @return bool
	 */
	public function isVerbose()
	{
		return $this->_verbose;
	}

	/**
	 * Prints coloured text to console without line break
	 * @param $message
	 * @param int|int[] $colour (optional)
	 */
	public function txt($message, $colour = 0)
	{
		$this->log($message, '', $colour);
		$this->_lastWasLine = false;
	}

	/**
	 * Prints coloured line to console
	 * @param $message
	 * @param string|null $end
	 * @param int|int[] $colour (optional)
	 */
	public function log($message, $end = PHP_EOL, $colour = 0)
	{
		if (!$this->_verbose) return;
		if (!is_array($colour)) $colour = [$colour];
		if ($this->_lastWasLoading) {
			$this->_lastWasLoading = false;
			echo PHP_EOL;
		}
		echo "\33[0;", implode(';', $colour), "m", $message, "\33[0m", ($end ?: '');
		$this->_lastWasProgress = false;
		$this->_lastWasLine = true;
	}

	/**
	 * Prints info message to console
	 * @param $message
	 * @param string|null $end (optional)
	 */
	public function inf($message, $end = PHP_EOL)
	{
		$this->log($message, $end, self::CLR_CYAN);
	}

	/**
	 * Prints success message to console
	 * @param $message
	 * @param string|null $end (optional)
	 */
	public function suc($message, $end = PHP_EOL)
	{
		$this->log($message, $end, self::CLR_GREEN);
	}

	/**
	 * Prints warning message to console
	 * @param $message
	 * @param string|null $end (optional)
	 */
	public function war($message, $end = PHP_EOL)
	{
		$this->log($message, $end, self::CLR_YELLOW);
	}

	/**
	 * Prints error message to console
	 * @param $message
	 * @param string|null $end (optional)
	 */
	public function err($message, $end = PHP_EOL)
	{
		$this->log($message, $end, self::CLR_RED);
	}

	/**
	 * Shows a loading line with message
	 * @param string $message
	 * @param int|int[] $colour (optional)
	 */
	public function loading($message, $colour = [])
	{
		static $cn;
		if (!$cn) {
			$cn = count(self::$LOADING_CHARS);
		}
		if ($this->_loadingChar >= $cn) {
			$this->_loadingChar = 0;
		}
		if (!$this->_loadingTime) {
			$this->_loadingTime = microtime(true);
		}
		$now = microtime(true);
		if ($now - $this->_loadingTime > self::$LOADING_WAIT) {
			$this->_loadingChar++;
			$this->_loadingTime = $now;
			$c = self::$LOADING_CHARS[$this->_loadingChar];
			if ($this->_lastWasLoading) echo "\r";
			echo self::C($colour), $message, " ", $c, "", self::C(self::CLR_RESET);
		}
		$this->_lastWasLoading = true;
		$this->_lastWasProgress = false;
		$this->_lastWasLine = false;
	}

	/**
	 * Prints progress
	 * @param $total
	 * @param int $done (optional)
	 * @param int $size (optional)
	 */
	public function progress($total, $done = 0, $size = 30)
	{
		if ($total < 1) {
			return;
		}
		$done = min($total, $done);
		$p = (double)($done / $total);
		$b = floor($p * $size);
		$d = $p * 100;
		$bar = "";
		if ($this->_lastWasProgress) {
			$bar .= "\r";
		} elseif ($this->_lastWasLoading) {
			$bar .= PHP_EOL;
		}
		$bar .= self::C(self::CLR_D_GRAY);
		$bar .= "[";
		$bar .= self::C(self::CLR_L_GRAY);
		$bar .= str_repeat("=", $b);
		if ($b < $size) {
			$bar .= self::C(self::CLR_WHITE);
			$bar .= "=";
			$bar .= self::C(self::CLR_RESET, self::CLR_D_GRAY);
			$bar .= str_repeat("-", $size - $b);
		} else {
			$bar .= "=";
		}
		$bar .= self::C(self::CLR_RESET, self::CLR_D_GRAY);
		$bar .= "] ";
		$bar .= self::C(self::CLR_RESET);
		$bar .= intval($d);
		$bar .= self::C(self::CLR_L_GRAY);
		$bar .= '% ';
		$bar .= sprintf("%d/%d", $done, $total);
		$bar .= self::C(self::CLR_RESET);
		echo "$bar ";
		flush();
		if ($done == $total) {
			echo PHP_EOL;
		}
		$this->_lastWasProgress = true;
		$this->_lastWasLine = false;
	}

}

class PhalconHintGenerator_AbortException extends Exception
{
	public function __construct($message = '', $code = 0, $previous = null)
	{
		$message = PHP_EOL . "User abort:" . PHP_EOL . "\33[0m" . preg_replace('/\\33\[[\d\;]+m/', '', $message) . PHP_EOL;
		parent::__construct($message, $code, $previous);
	}
}

abstract class PhalconHintGenerator_Base extends PhalconHintGenerator_Message
{
	const EXT_ZEPHIR = 'zep';
	const EXT_PHP = 'php';

	const INJECT_TXT = 'phalcon-hint-services.txt';
	const INJECT_NAMESPACE = 'Phalcon\Di';
	const INJECT_CLASSNAME = 'Injectable';

	const PHALCON_VERSION_FILE = 'version';
	const PHALCON_VERSION_REGEX = '/protected\s+static\s+function\s+\_getVersion\(\)\s\-\>\s+array.*?\{.*?return\s+\[\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d]+)\s*]\s*;/ms';

	const TYPE_ZEPHIR = 1;
	const TYPE_DOCBLOCK = 2;
	const TYPE_PARAMETER = 3;

	public static $TYPES = [
		'var'           => self::TYPE_ZEPHIR,
		'long'          => self::TYPE_ZEPHIR,
		'unsigned long' => self::TYPE_ZEPHIR,
		'unsigned int'  => self::TYPE_ZEPHIR,
		'unsigned char' => self::TYPE_ZEPHIR,
		'char'          => self::TYPE_ZEPHIR,
		'string'        => self::TYPE_DOCBLOCK,
		'int'           => self::TYPE_DOCBLOCK,
		'float'         => self::TYPE_DOCBLOCK,
		'double'        => self::TYPE_DOCBLOCK,
		'bool'          => self::TYPE_DOCBLOCK,
		'boolean'       => self::TYPE_DOCBLOCK,
		'null'          => self::TYPE_DOCBLOCK,
		'void'          => self::TYPE_DOCBLOCK,
		'mixed'         => self::TYPE_PARAMETER,
		'array'         => self::TYPE_PARAMETER,
		'object'        => self::TYPE_PARAMETER,
		'resource'      => self::TYPE_PARAMETER,
		'callable'      => self::TYPE_PARAMETER,
	];

	public static $REMAP_TYPES = [
		'var'           => 'mixed',
		'long'          => 'int',
		'unsigned long' => 'int',
		'unsigned int'  => 'int',
		'unsigned char' => 'string',
		'char'          => 'string',
	];

	/**
	 * Fix slashes in path
	 * @param $path
	 * @return string
	 */
	public static function FixSlashes($path)
	{
		$sl = strlen($path);
		if ($sl < 1) {
			return self::DS;
		}
		if ($path[0] !== self::DS) {
			$path = self::DS . $path;
			$sl++;
		}
		if ($sl > 2 && $path[$sl - 1] === self::DS) {
			$path = substr($path, 0, $sl - 1);
		}
		return $path;
	}

	/**
	 * Converts namespace to path
	 * @param $namespace
	 * @return mixed
	 */
	public static function NamespaceToPath($namespace)
	{
		return str_replace('\\', self::DS, $namespace);
	}

	/** @var bool */
	protected $_force = false;

	public function force($force = true)
	{
		$this->_force = $force;
	}

	public function isForce()
	{
		return $this->_force;
	}

	/**
	 * @return void
	 */
	abstract function process();

}

class PhalconHintGenerator_File extends PhalconHintGenerator_Base
{

	const RGX_NAMESPACE = '/namespace\s+(?<name>(?:\\\\?[\w\d_]+)+)\s*;/ims';
	const RGX_CLASSNAME = '/(((?:(?:abstract|final)\s*)+)?(class|interface)\s*\$?(?<name>[\w\d\_]+)(?:\s+extends\s*([\\\\\w\d_]+))?(?:\s+implements\s+((?:\s*\,?\s*[\\\\\w\d_]+)+))?)(?:\s*{)/ims';
	const RGX_USE = '/use\s+(?<path>(?:\\\\?[\w\d_]+)+)(?:\s+as\s(?<alias>[\w\d_]+))?\s*;/ims';
	const RGX_CONSTANT = '/\s+const\s+(?<name>[\w\d_]+)(?:\s*=\s*(?<def>(\'.*?\')|(".*?")|([\w\d\_\:\s\\\\]+)))\s*;/ims';
	const RGX_PROPERTY = '/(?<mod>(?:(?:public|protected|private|static)\s+)+)(?<name>[\w\d_]+)(?:\s+=(?<def>.*?))?(?:\s*{(?<exp>\s*(?:[\w\d_]+\s*\,?\s*)+)})?;/ims';
	const RGX_METHOD = '/(?<mod>(?:(?:public|protected|private|final|static|abstract)\s+)+)function\s*\$?(?<name>[\w\d_]+)\s*\((?<param>.*?)\)(?:\s*\-\>(?<ret>(?:\s*\|?\s*[\w\d_\<\>\\\\\[\]]+)+))?.*?(?<end>{|;)/ims';
	//'/(?<mod>(?:(?:public|protected|private|final|static|abstract)\s+)+)function\s*\$?(?<name>[\w\d_]+)\s*\(?<prm>(.*?)\)(?:\s*\-\>(?<ret>(?:\s*\|?\s*[\w\d_\<\>\\\\\[\]]+)+))?.*?(?<end>{|;)/ims';
	const RGX_METHOD_PARAM_1 = '/^<(?<type>[^>]+)>\s+(?<name>[\w\d_]+)\s*(?:\=\s*(?<def>.*))?$/ims';
	const RGX_METHOD_PARAM_2 = '/^(?<type>[\w\d_\[\]]+)(!?)\s+(?<name>[\w\d_]+)\s*(?:\=\s*(?<def>.*))?$/ims';
	const RGX_METHOD_PARAM_3 = '/^(?<name>[\w\d_]+)\s*(?:\=\s*(?<def>.*))?$/ims';
	const RGX_METHOD_RETURNS = '/[\s\t\n]return[\s\t\n\;]/ims';

	/** @var PhalconHintGenerator */
	protected $_generator;
	/** @var string */
	protected $_path;
	/** @var string */
	protected $_source;
	/** @var string */
	protected $_clean;
	/** @var string */
	protected $_result;
	/** @var array */
	protected $_use = [];
	/** @var array */
	protected $_useAs = [];
	/** @var string */
	protected $_namespace;
	/** @var string */
	protected $_namespaceLine;
	/** @var int */
	protected $_namespaceOffset;
	/** @var string */
	protected $_className;
	/** @var string */
	protected $_classNameLine;
	/** @var int */
	protected $_classNameOffset;
	/** @var array */
	protected $_comments = [];
	/** @var int */
	protected $_commentFrom = 0;

	/**
	 * @param PhalconHintGenerator $generator
	 * @param string $file (optional)
	 */
	public function __construct(PhalconHintGenerator $generator, $file = null)
	{
		$this->_generator = $generator;
		$this->verbose($this->_generator->isVerbose());
		if ($file) $this->setPath($file);
	}

	/**
	 * Sets the source file path
	 * @param $path
	 * @throws PhalconHintGenerator_Exception
	 */
	public function setPath($path)
	{
		if (!is_readable($path)) {
			throw new PhalconHintGenerator_Exception("Failed to read file: {$path}");
		}
		$this->_path = $path;
		$this->_source = file_get_contents($this->_path);
		$this->reset();
	}

	/**
	 * Clear properties
	 */
	public function reset()
	{
		$this->_clean = '';
		$this->_result = '';
		$this->_use = [];
		$this->_useAs = [];
		$this->_namespace = false;
		$this->_namespaceOffset = 0;
		$this->_namespaceLine = false;
		$this->_className = false;
		$this->_classNameOffset = 0;
		$this->_classNameLine = false;
		$this->_comments = [];
		$this->_commentFrom = 0;
	}

	/**
	 * Get list of relative classes for namespace
	 * @return array
	 * @throws PhalconHintGenerator_Exception
	 */
	public function getRelativeClasses()
	{
		return $this->_generator->getRelativeClasses($this->getNamespace());
	}

	/**
	 * Check if class name is in relative namespace
	 * @param $className
	 * @return bool
	 * @throws PhalconHintGenerator_Exception
	 */
	public function isRelativeClass($className)
	{
		return $this->_generator->isRelativeClass($this->getNamespace(), $className);
	}

	/**
	 * Get the namespace of the file
	 * @return string
	 * @throws PhalconHintGenerator_Exception
	 */
	public function getNamespace()
	{
		if (!$this->_namespace) {
			$this->stripComments();
			preg_match(self::RGX_NAMESPACE, $this->_clean, $m, PREG_OFFSET_CAPTURE);
			$this->_namespace = $m['name'][0];
			$this->_namespaceOffset = $m[0][1];
			$this->_namespaceLine = trim($m[0][0]);
		}
		return $this->_namespace;
	}

	/**
	 * Get the name of class in the file
	 * @return string
	 * @throws PhalconHintGenerator_Exception
	 */
	public function getClassName()
	{
		if (!$this->_className) {
			$this->stripComments();
			preg_match(self::RGX_CLASSNAME, $this->_clean, $m, PREG_OFFSET_CAPTURE);
			$this->_className = trim($m['name'][0]);
			$this->_classNameOffset = $m[0][1];
			$this->_classNameLine = trim(str_replace('$', '', $m[0][0]));
		}
		return $this->_className;
	}

	/**
	 * Remove every comment from the file and put in an array
	 * @param bool $force
	 * @throws PhalconHintGenerator_Exception
	 */
	public function stripComments($force = false)
	{
		if (!$force && strlen($this->_clean) > 0) {
			return;
		}
		$this->_clean = '';
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
		for ($i = 0; $i < $len; $i++) {
			$c = $this->_source[$i];
			$cp1 = $this->_source[$i + 1] ?: false;
			$cp2 = $this->_source[$i + 2] ?: false;
			if ($c == '\\') {
				if ($cm1 == '\\') {
					$escape++;
				} else {
					$escape = 1;
				}
			} else {
				if ($escape % 2 == 0) {
					if ($c == "'" && !$s2 && !$c1 && !$c2) {
						$skip = false;
						$skipNum = 0;
						$s1 = !$s1;
						$offset = false;
					} elseif ($c == '"' && !$s1 && !$c1 && !$c2) {
						$skip = false;
						$skipNum = 0;
						$s2 = !$s2;
						$offset = false;
					} elseif ($c == '/') {
						if (!$s1 && !$s2 && !$c1 && !$c2 && $cp1 == '/') {
							$skip = true;
							$skipNum = 0;
							$c1 = true;
							$offset = false;
						} elseif (!$s1 && !$s2 && !$c1 && !$c2 && $cp1 == '*' && $cp2 == '*') {
							$skip = true;
							$skipNum = 0;
							$c2 = true;
							$offset = $i;
						}
					} elseif ($c == '*' && $c2 && $cp1 == '/') {
						$skip = true;
						$skipNum = 2;
						$c2 = false;
					} elseif ($c == "\n" && $c1) {
						$skip = false;
						$skipNum = 1;
						$c1 = false;
						$offset = false;
					}
				}
				$escape = 0;
			}
			if ($skipNum == 0 && !$c1 && !$c2) {
				$skip = false;
			}
			if (!$skip || $c == "\r" || $c == "\n") {
				$this->_clean .= $c;
			} else {
				$this->_clean .= " ";
			}
			if (!$skip) {
				if (strlen($comment) > 0 && $offset !== false) {
					$this->_comments[] = [
						'offset'  => $offset,
						'length'  => strlen($comment),
						'comment' => $comment,
					];
					$comment = '';
					$offset = false;
				}
			} elseif ($offset !== false) {
				$comment .= $c;
			}
			if ($skipNum > 0) $skipNum--;
			if ($i > 0) $cm1 = $c;
		}
		if (strlen($this->_clean) != strlen($this->_source)) {
			throw new PhalconHintGenerator_Exception("Failed to consistently strip comments for {$this->_path}");
		}
	}


	protected function getComment($from, $to, $types = [], $return = false)
	{
		$comment = false;
		$hasTypes = is_array($types) && count($types) > 0;
		$hasReturn = strlen(trim($return)) > 0;
		foreach ($this->_comments as $c) {
			$s = intval($c['offset']);
			$e = $s + intval($c['length']);
			if ($s >= $from && $e <= $to) {
				$comment = trim($c['comment']);
				break;
			}
		}
		if (!$comment) {
			if (!$hasTypes && !$hasReturn) {
				return '';
			}
			$comment = "/**" . PHP_EOL . "\t */";
		}
		if ($hasTypes) {
			preg_match_all('/\@param\s+([\w\d_\|\s\\\\\[\]]+)\s+([\w\d_]+)/i', $comment, $m);
			foreach ($m[0] as $mk => $mv) {
				$mName = trim($m[2][$mk]);
				$mType = trim($m[1][$mk]);
				$types[$mName] = $types[$mName] ?: $mType;
			}
			$comment = preg_replace('/^\s*\*?\s*@param\s+(.*?)\r?\n/im', '', $comment);
			$params = '';
			foreach ($types as $name => $type) {
				$type = $this->expandUseAs($type);
				$params .= PHP_EOL . "\t * @param " . ($type ? $type . ' ' : '') . '$' . $name;
			}
			$comment = preg_replace_callback('/\s*\r?\n\s*(\*\s*)?(\@return\s+.*?)?\*\//ims', function ($m) use ($params) {
				return (!$m[2] ? PHP_EOL . "\t * " : '') . $params . ($m[2] ? PHP_EOL . "\t * " : '') . $m[0];
			}, $comment);
		}
		if ($return !== false) {
			preg_match('/\@return\s+([\w\d_\|\s\\\\\[\]]+)/i', $comment, $m);
			$return = str_replace(['<', '>'], '', trim($return));
			if ($m[0]) {
				$return = $this->expandUseAs($return ?: $m[1]);
				$n = '@return ' . $return;
				$comment = str_replace($m[0], $n . PHP_EOL . "\t ", $comment);
			} elseif ($hasReturn) {
				$return = $this->expandUseAs($return);
				$r = '@return ' . $return;
				$comment = str_replace('*/', '*' . PHP_EOL, $comment);
				$comment .= "\t * " . $r . PHP_EOL;
				$comment .= "\t */";
			}
			$comment = preg_replace_callback('/\@(param|return)\s+var($|\s)/i', function ($m) {
				return '@' . $m[1] . ' mixed' . $m[2];
			}, $comment);
		}
		return "\t" . $comment . PHP_EOL;
	}

	protected function expandUseAs($type)
	{
		$type = trim($type);
		if (strlen($type) < 1) {
			return '';
		}
		$types = explode('|', $type);
		$return = [];
		foreach ($types as $type) {
			$type = $safeType = trim($type);
			$isArray = substr($type, -2, 2) == '[]';
			if ($isArray) {
				$safeType = substr($type, 0, -2);
			}
			if ($safeType == $this->_className) {
				$return[] = $type;
			} elseif (array_key_exists($safeType, self::$TYPES)) {
				if (self::$TYPES[$safeType] == self::TYPE_ZEPHIR) {
					continue;
				}
				$return[] = $type;
			} elseif (array_key_exists($safeType, $this->_use) || array_key_exists($safeType, $this->_useAs)) {
				$return[] = $type;
			} elseif ($this->isRelativeClass($safeType)) {
				$return[] = $type;
			} else {
				if ($type[0] !== '\\') {
					$parts = explode('\\', $safeType);
					if (array_key_exists($parts[0], $this->_use) || array_key_exists($parts[0], $this->_useAs)) {

					} else {
						$type = '\\' . $type;
					}
				}
				$return[] = $type;
			}
		}
		return implode('|', $return);
	}

	public function processNamespace()
	{
		$this->getNamespace();
		$this->_result .= $this->_namespaceLine . PHP_EOL . PHP_EOL;
	}

	public function processUses()
	{
		preg_match_all(self::RGX_USE, $this->_clean, $m, PREG_OFFSET_CAPTURE);
		foreach ($m[0] as $mk => $mv) {
			$classPath = trim($m['path'][$mk][0]);
			$as = trim($m['alias'][$mk][0]);
			if (strlen($as) < 1) {
				$parts = explode('\\', $classPath);
				$as = array_pop($parts);
				if ($as == $this->_className) {
					continue;
				}
				foreach ($this->_use as $k => &$v) {
					if (strcasecmp($k, $as) === 0) {
						continue 2;
					}
				}
				$this->_use[$as] = $classPath;
			} else {
				if (array_key_exists($as, $this->_useAs) || $as == $this->_className) {
					continue;
				}
				$this->_useAs[$as] = $classPath;
			}
			$this->_result .= trim($mv[0]) . PHP_EOL;
		}
		if ($m[0]) $this->_result .= '' . PHP_EOL . PHP_EOL;
	}

	public function processClassName()
	{
		$this->getClassName();
		$this->_result .= $this->_classNameLine . PHP_EOL . PHP_EOL;
		$this->_commentFrom = $this->_classNameOffset;
	}

	public function processConstants()
	{
		preg_match_all(self::RGX_CONSTANT, $this->_clean, $m, PREG_OFFSET_CAPTURE);
		foreach ($m[0] as $mk => $mv) {
			$s = trim($mv[0]);
			$p = intval($mv[1]);
			$l = strlen($s);
			$c = $this->getComment($this->_commentFrom, $p);
			$this->_result .= $c . "\t" . $s . PHP_EOL . PHP_EOL;
			$this->_commentFrom = $p + $l;
		}
		if ($m[0]) $this->_result .= '' . PHP_EOL . PHP_EOL;
	}

	public function processProperties()
	{
		$inject_services = [];
		if ($this->_className == self::INJECT_CLASSNAME && $this->_namespace == self::INJECT_NAMESPACE && is_file(__DIR__ . self::DS . self::INJECT_TXT)) {
			$fh = fopen(__DIR__ . self::DS . self::INJECT_TXT, 'r');
			if ($fh) {
				while (($line = fgets($fh))) {
					$line = preg_replace('/[\t\s]+/', "\t", $line);
					$line = explode("\t", $line);
					if (count($line) > 1) {
						$inject_services[trim($line[0])] = trim($line[1]);
					}
				}
				fclose($fh);
			}
		}
		preg_match_all(self::RGX_PROPERTY, $this->_clean, $m, PREG_OFFSET_CAPTURE);
		foreach ($m[0] as $mk => $mv) {
			$type = trim($m['mod'][$mk][0]);
			$name = trim($m['name'][$mk][0]);
			$default = trim($m['def'][$mk][0]);
			$dl = strlen($default);
			if ($dl > 1 && $default[0] == '"' && $default[$dl - 1] == '"') {
				$default[0] = $default[$dl - 1] = "'";
			}
			$methods = trim($m['exp'][$mk][0]);
			$comment = $this->getComment($this->_commentFrom, $m[0][$mk][1]);

			$this->_result .= $comment . "\t" . $type . ' $' . $name . ($default ? ' = ' . $default : '') . ';' . PHP_EOL . PHP_EOL;
			if (strlen($methods)) {
				$methods = explode(',', $methods);
				foreach ($methods as $me) {
					$me = trim($me);
					$mName = $name;
					if ($mName[0] == '_') $mName = substr($mName, 1);
					if ($me == 'set') $me = $me . ucfirst($mName) . '($value) {' . PHP_EOL . "\t\t" . '$this->' . $name . ' = $value;' . PHP_EOL . "\t}" . PHP_EOL;
					elseif ($me == 'get') $me = $me . ucfirst($mName) . "() {" . PHP_EOL . "\t\t" . 'return $this->' . $name . ';' . PHP_EOL . "\t}" . PHP_EOL;
					else $me = $me . "() {" . PHP_EOL . "\t\t" . 'return $this->' . $name . ";" . PHP_EOL . "\t}" . PHP_EOL;
					$this->_result .= "\tpublic function " . $me . PHP_EOL;
				}
			}
			if (array_key_exists($name, $inject_services)) unset($inject_services[$name]);
			$this->_commentFrom = $m[0][$mk][1] + strlen($m[0][$mk][0]);
		}
		if ($m[0]) $this->_result .= '' . PHP_EOL . PHP_EOL;

		if (count($inject_services) > 0) {
			foreach ($inject_services as $name => $type) {
				$line = "\t/**" . PHP_EOL . "\t * @var " . $type . PHP_EOL . "\t */" . PHP_EOL . "\t" . 'public $' . $name . ';' . PHP_EOL . PHP_EOL;
				$this->_commentFrom += strlen($line);
				$this->_result .= $line;
			}
			$this->_result .= '' . PHP_EOL . PHP_EOL;
		}
	}

	public function processMethods()
	{
		preg_match_all(self::RGX_METHOD, $this->_clean, $m, PREG_OFFSET_CAPTURE);
		foreach ($m[0] as $mk => $mv) {
			$type = trim($m['mod'][$mk][0]);
			$name = trim($m['name'][$mk][0]);
			$prms = trim($m['param'][$mk][0]);
			$hint = trim($m['ret'][$mk][0]);
			$returns = false;
            $abstract = $m['end'][$mk][0] !== '{';

			if ($abstract) {
				$end = ';';
			} else {
				$end = ' {}';
				$s1 = false;
				$s2 = false;
				$bra = 1;
				$esc = 0;
				$i = $m['end'][$mk][1];
				while ($bra > 0 && !$returns) {
					$i++;
					$c = $this->_clean[$i];
					$c1 = $this->_clean[$i - 1];
					if ($c == '\\') {
						if ($c1 == '\\') {
							$esc++;
						} else {
							$esc = 1;
						}
					} else {
						if ($esc % 2 == 0) {
							if (!$s2 && $c == "'") {
								$s1 = !$s1;
							} elseif (!$s1 && $c == '"') {
								$s2 = !$s2;
							} elseif (!$s1 && !$s2) {
								if ($c == '{') $bra++;
								elseif ($c == '}') $bra--;
								else {
									if (preg_match(self::RGX_METHOD_RETURNS, substr($this->_clean, $i, 8))) {
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

			$line = "\t" . $type . " function " . $name . "(";

			$prmse = explode(',', $prms);
			$prms = [];
			$types = [];
			foreach ($prmse as $prm) {
				$prm = trim($prm);
				$pType = $pName = $pDef = false;
				if (preg_match(self::RGX_METHOD_PARAM_1, $prm, $mp)) {
					$pType = $mp['type'];
					$pName = $mp['name'];
					$pDef = $mp['def'] ?: false;
				} elseif (preg_match(self::RGX_METHOD_PARAM_2, $prm, $mp)) {
					$pType = $mp['type'];
					$pName = $mp['name'];
					$pDef = $mp['def'] ?: false;
				} elseif (preg_match(self::RGX_METHOD_PARAM_3, $prm, $mp)) {
					$pType = $mp['type'];
					$pName = $mp['name'];
					$pDef = $mp['def'] ?: false;
				}
				if ($pName) {
					$pTypeSafe = '';
					if (self::$TYPES[$pType] === self::TYPE_PARAMETER) $pTypeSafe = $pType;
					elseif (array_key_exists($pType, self::$REMAP_TYPES)) $pType = self::$REMAP_TYPES[$pType];
					elseif (!array_key_exists($pType, self::$TYPES)) $pTypeSafe = $pType;
					if ($pTypeSafe[0] === '$') $pTypeSafe = substr($pTypeSafe, 1);
					$p = trim($pTypeSafe . ' $' . $pName);
					if ($pDef) $p .= "=" . trim($pDef);
					$prms[] = $p;
					$types[$pName] = trim($pType);
				}
			}

			$line .= implode(', ', $prms) . ")" . $end;
            $hint = trim($hint);
            if(!$hint) {
                if($abstract || $name==='__construct') {
                    $hint = false;
                }
                else {
                    if($returns) {
                        $hint = 'mixed';
                    }
                    else {
                        $hint = 'void';
                    }
                }
            }
            $comment = $this->getComment($this->_commentFrom, $m[0][$mk][1], $types, $hint);

			$this->_result .= $comment . $line . PHP_EOL . PHP_EOL;

			$this->_commentFrom = $m[0][$mk][1] + strlen($m[0][$mk][0]);
		}
	}

	/**
	 * Save result to output location
	 * @throws PhalconHintGenerator_Exception
	 */
	public function save()
	{
		$path = $this->_generator->createPath(self::NamespaceToPath($this->getNamespace())) . self::DS . $this->getClassName() . '.' . self::EXT_PHP;;
		file_put_contents($path, $this->_result);
	}

	public function process()
	{
		$this->_result = '<?php' . PHP_EOL . PHP_EOL;
		$this->stripComments();
		$this->getClassName();
		$this->getNamespace();
		$this->processNamespace();
		$this->processUses();
		$this->processClassName();
		$this->processConstants();
		$this->processProperties();
		$this->processMethods();
		$this->_result .= '}' . PHP_EOL;
		$this->save();
	}

	/**
	 * Returns the PHP output
	 * @return string
	 */
	public function __toString()
	{
		return $this->_result;
	}


}

class PhalconHintGenerator extends PhalconHintGenerator_Base
{

	/** @var float */
	protected $_start;
	/** @var string */
	protected $_src;
	/** @var string */
	protected $_out;
	/** @var PhalconHintGenerator_File */
	protected $_file;
	/** @var int */
	protected $_fileCount;
	/** @var string[] */
	protected $_classList = [];


	/**
	 * Create instance for hint generation
	 * @param string $src
	 * @param string $out
	 */
	public function __construct($src, $out)
	{
		$this->_src = realpath($src);
		$this->_out = realpath($out);
		$this->_file = new PhalconHintGenerator_File($this);
	}

	/**
	 * Set verbose on/off
	 * @param bool $verbose
	 */
	public function verbose($verbose = true)
	{
		parent::verbose($verbose);
		$this->_file->verbose($verbose);
	}

	/**
	 * Don't ask any questions, just do it
	 * @param bool $force
	 */
	public function force($force = true)
	{
		parent::force($force);
		$this->_file->force(true);
	}

	/**
	 * Return the output directory
	 * @return string
	 */
	public function getOutDir()
	{
		return $this->_out;
	}

	/**
	 * Returns the elapsed time since the start of processing
	 * @param bool $format
	 * @return mixed|string
	 */
	public function elapsedTime($format = false)
	{
		$elapsed = microtime(true) - $this->_start;
		if (is_string($format)) return sprintf($format, $elapsed);
		return $elapsed;
	}

	/**
	 * Attempts to find the version for Phalcon and appends it to the output directory
	 * @return bool
	 * @throws PhalconHintGenerator_AbortException
	 */
	public function findPhalconVersion()
	{
		$file = $this->_src . self::DS . self::PHALCON_VERSION_FILE . '.' . self::EXT_ZEPHIR;
		$err = false;
		if (!is_readable($file)) {
			$err = "Phalcon version file not found: " . PHP_EOL . self::C(self::CLR_UNDERLINE) . "{$file}";
		} else {
			$version = file_get_contents($file);
			if (preg_match(self::PHALCON_VERSION_REGEX, $version, $m)) {
				$version = $m[1] . '.' . $m[2] . '.' . $m[3] . '.' . $m[4];
				$this->_out .= self::DS . $version;
				if (!is_dir($this->_out)) mkdir($this->_out, 0777, false);
				$div = "\t" . '============================' . str_repeat('=', strlen($version));
				$this->log($div, PHP_EOL, self::CLR_D_GRAY);
				$this->log("\t" . "Recognized Phalcon version: " . self::C(self::CLR_BOLD, self::CLR_CYAN) . "{$version}", PHP_EOL, self::CLR_L_GRAY);
				$this->log($div, PHP_EOL . PHP_EOL, self::CLR_D_GRAY);
				return true;
			}
		}
		if (!$err) $err = "Failed to parse Phalcon version from " . PHP_EOL . self::C(self::CLR_UNDERLINE) . "{$file}";
		$this->war($err);
		if (!$this->isForce()) {
			$this->suc('No sweat, you may still use it for Zephir projects.' . PHP_EOL . '(No versioned subdirectory at output or property injections from text file.)');
			if (!$this->confirm('Continue?')) {
				throw new PhalconHintGenerator_AbortException($err);
			}
		}
		return false;
	}

	/**
	 * Removes every file and folder in the output directory
	 * @throws PhalconHintGenerator_AbortException
	 */
	public function clearOutDir()
	{
		if (count(scandir($this->_out)) > 2) {
			$err = "Target directory is not empty";
			if (!$this->isForce() && !$this->confirm("{$err}, are you sure?")) {
				throw new PhalconHintGenerator_AbortException($err);
			}
		}
		$di = new RecursiveDirectoryIterator($this->_out, RecursiveDirectoryIterator::SKIP_DOTS);
		$fi = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
        $this->log("Clearing output directory", PHP_EOL, self::CLR_CYAN);
		foreach ($fi as $i) {
			$this->loading(self::C(self::CLR_CYAN) . "Clearing output directory");
			if ($i->isDir()) {
				rmdir($i->getRealPath());
			} else {
				unlink($i->getRealPath());
			}
		}
		$this->suc("Done");
	}

	/**
	 * Creates path in the output directory, return the real path for it
	 * @param $path
	 * @return string
	 * @throws PhalconHintGenerator_Exception
	 */
	public function createPath($path)
	{
		$path = $this->_out . self::fixSlashes($path);
		if (is_dir($path)) {
			return $path;
		}
		if (!mkdir($path, 0777, true)) {
			throw new PhalconHintGenerator_Exception("Failed to create output directory: {$path}");
		}
		return $path;
	}

	/**
	 * Creates a list of classes with namespace
	 * @throws PhalconHintGenerator_Exception
	 */
	public function buildClassList()
	{
		$this->loading("Building class tree", self::CLR_CYAN);
		$this->_fileCount = 0;
		$di = new RecursiveDirectoryIterator($this->_src, RecursiveDirectoryIterator::SKIP_DOTS);
		$fi = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($fi as $i) {
			if ($i->isFile() && $i->getExtension() === self::EXT_ZEPHIR) {
				$this->_fileCount++;
				$file = $i->getRealPath();
				$this->_file->setPath($file);
				$path = substr($file, strlen($this->_src));
				$ns = $this->_file->getNamespace();
				$cn = $this->_file->getClassName();
				$this->_classList[$path] = $ns . '\\' . $cn;
				$this->loading("Building class tree", self::CLR_CYAN);
			}
		}
		if ($this->_fileCount < 1) {
			throw new PhalconHintGenerator_Exception("No classes found in source directory!");
		}
		$this->suc("Class tree done, {$this->_fileCount} files");
	}

	/**
	 * Get list of relative classes for namespace
	 * @param $namespace
	 * @return array
	 * @throws PhalconHintGenerator_Exception
	 */
	public function getRelativeClasses($namespace)
	{
		if (count($this->_classList) < 1) {
			throw new PhalconHintGenerator_Exception("Class list has not been built yet!");
		}
		$classes = [];
		$l = strlen($namespace);
		foreach ($this->_classList as &$c) {
			if (strpos($c, $namespace . '\\') === 0) {
				$r = substr($c, $l + 1);
				$classes[] = $r;
			}
		}
		return $classes;
	}

	/**
	 * Check if class name is in relative namespace
	 * @param $namespace
	 * @param $className
	 * @return bool
	 * @throws PhalconHintGenerator_Exception
	 */
	public function isRelativeClass($namespace, $className)
	{
		if (count($this->_classList) < 1) {
			throw new PhalconHintGenerator_Exception("Class list has not been built yet!");
		}
		$l = strlen($namespace);
		foreach ($this->_classList as &$c) {
			if (strpos($c, $namespace . '\\') === 0) {
				$r = substr($c, $l + 1);
				if ($r === $className) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Generate the hints
	 * @throws PhalconHintGenerator_AbortException
	 * @throws PhalconHintGenerator_Exception
	 */
	public function process()
	{
		$this->_start = microtime(true);
		if (!is_readable($this->_src)) throw new PhalconHintGenerator_Exception("Can't read source directory: {$this->_src}");
		if (!is_dir($this->_out)) throw new PhalconHintGenerator_Exception("Output directory does not exist: {$this->_out}");
		if (!is_writable($this->_out)) throw new PhalconHintGenerator_Exception("Can't write to output directory: {$this->_out}");
		$this->findPhalconVersion();
		$this->clearOutDir();
		$this->buildClassList();
		$this->inf('Generating stubs...');
		$n = 0;
		$this->progress($this->_fileCount, $n);
		foreach ($this->_classList as $zep => &$cls) {
			$this->_file->setPath($this->_src . $zep);
			$this->_file->process();
			$this->progress($this->_fileCount, ++$n);
		}
		$this->suc("Done generating!");
		$this->inf(sprintf("%0.2f s, %.0f kB", $this->elapsedTime(), memory_get_peak_usage() / 1024));
	}

}
