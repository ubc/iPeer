<?php
/**
 * Low entrophy recovery passwords may be guessed relatively easily by an
 * attacker, this module attempts to generate more secure recovery passwords.
 *
 * Both of the built in PHP random number generators, rand() and mt_rand()
 * are considered inadequate for crytopgraphic usages. A more secure built-in
 * random number generator is in discussion, but for now, we have to make do
 * with a few work arounds to get quality random numbers.
 * */
class PasswordGeneratorComponent extends CakeObject {

  public $name = 'PasswordGenerator';

  /**
   * Generate a secure password by first generating a random number of $len
   * bytes and then converting the resulting binary string into something
   * printable.
   *
   * To maintain cross-platform compatibility, we have 3 different methods
   * for generating the secure method.
   * - Using the OpenSSL extension
   * - Using /dev/urandom on Linux/Unix
   * - Using the Windows API
   *
   * @param int $len - strength of the password in bytes, defaults to 16
   *
   * @return a printable password string
   * */
  public function generate($len=16) {
    $ret = $this->tryOpenSSL($len);
    if (!empty($ret)) {
      return $ret;
    }

    $ret = $this->tryLinux($len);
    if (!empty($ret)) {
      return $ret;
    }

    $ret = $this->tryWindows($len);
    if (!empty($ret)) {
      return $ret;
    }

    // fall back password algorithm
    return $this->defaultAlgorithm($len);
  }

  /**
   * Convert a binary string into a printable string. Basically performs a
   * base64 conversion and then remove the trailing '=' delimiters.
   *
   * @param string $bin - the binary string
   *
   * @return the binary string represented by printable characters
   * */
  private function binconvert($bin) {
    $ret = base64_encode($bin);
    $ret = str_replace("=", "", $ret);
    return $ret;
  }

  /**
   * Use the OpenSSL PHP extension to generate a secure password. We
   * basically generate a random number of $len bytes and convert the
   * resulting binary string to something user readable.
   *
   * Helper for generate().
   *
   * @param int $len - strength of the password in bytes
   *
   * @return a printable password string
   * */
  private function tryOpenSSL($len) {
    $output = "";
    if (function_exists('openssl_random_pseudo_bytes')) {
      $output = openssl_random_pseudo_bytes($len, $strong);

      if ($strong !== true) {
        // not cryptographically strong, so discard
        return "";
      }
    }
    return $this->binconvert($output);
  }

  /**
   * Uses built-in Linux special files to generate a secure password. We
   * basically generate a random number of $len bytes and convert the
   * resulting binary string to something user readable.
   *
   * Helper for generate().
   *
   * @param int $len - strength of the password in bytes
   *
   * @return a printable password string
   * */
  private function tryLinux($len) {
    // Note that /dev/urandom is less secure than /dev/random as /dev/random
    // will block in order to collect more entrophy if necessary while
    // /dev/urandom will try to make do with what it has. However, we're using
    // urandom since the blocking random call is unpredictable and may take
    // from minutes to hours for it to get enough randomness.
    $ret = '';
    if (@is_readable('/dev/urandom')) {
      $f = fopen('/dev/urandom', 'r');
      $ret = fread($f, $len);
      fclose($f);
    }
    return $this->binconvert($ret);
  }

  /**
   * Uses Windows API to generate a secure password. We basically generate
   * a random number of $len bytes and convert the resulting binary string
   * to something user readable.
   *
   * This relies on the now obsolete CAPICOM library from MS:
   * http://www.microsoft.com/en-us/download/details.aspx?id=25281
   * Which is no longer supported after Vista. It was replaced with the
   * .net x509 crypto lib.
   *
   * Helper for generate().
   *
   * @param int $len - strength of the password in bytes
   *
   * @return a printable password string
   * */
  private function tryWindows($len) {
    $pr_bits = "";
    if (@class_exists('COM')) {
      // http://msdn.microsoft.com/en-us/library/aa388176(VS.85).aspx
      try {
        $CAPI_Util = new COM('CAPICOM.Utilities.1');
        $pr_bits .= $CAPI_Util->GetRandom($len, 0);

        // if we ask for binary data PHP munges it, so we
        // request base64 return value.  We squeeze out the
        // redundancy and useless ==CRLF by hashing...
        if ($pr_bits) {
            $pr_bits = $this->binconvert($pr_bits, true);
        }
      } catch (Exception $ex) {
        return "";
      }
    }

    return $pr_bits;
  }

  /**
   * defaultAlgorithm
   * fallback password generating algorithm
   *
   * @param mixed $length
   * @param mixed $chars
   *
   * @access private
   * @return void
   */
  private function defaultAlgorithm( $length = 8, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ) {
    return substr(str_shuffle($chars), 0, $length);
  }
}
