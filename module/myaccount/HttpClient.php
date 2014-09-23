<?php

/*
class HttpClient

��������

PHP5
(����ʹ�� https ���ߵĻ�, ����php����װ OpenSSLģ��)
*/

#��������
define( "CRLF", "\r\n" );

class HttpClient
{
			
		# ��Ա����
		var $debug;
		
		# ������
		function __construct()
		{
		   # ������
		   #$this->debug = true;
		
		   # ��ʽ��
		   $this->debug = false;
		}
		
		# �⹹��
		function __destruct()
		{
		
		}
		
		
		/*
		   ��;:
		    Debug ��ѶϢ
		   ��������ֵ:
		    ������Ĳ���������, �Զ�����
		*/
		function DebugOut()
		{
		   if ($this->debug == true) 
		   {
		    $numargs = func_num_args();
		    $arg_list = func_get_args();
		
		    for ($i = 0; $i < $numargs; $i++)
		    {
		         $s = $arg_list[$i];
		
		     if (is_object($s))
		     {
		      var_dump($s);
		      echo "\n";
		     }
		     else if (is_array($s))
		     {
		      print_r($s);
		      echo "\n";
		     }
		     else
		     {
		      echo $s."\n";
		     }
		    }
		   }
		}
		
		
		/*
		   ��;:
		    Http Request��
		
		   ��������ֵ:
		    $url = ��ַ
		    $method = ����ģʽ ('GET'��'POST')
		    $data = ����Ĳ��� (NULL, ����array��ʽ����)
		    $additional_headers = �����Զ���http��ͷ
		    $followRedirects = �Ƿ�֧Ԯ�Զ�תַ
		
		   ��������ֵ:
		    ����ַ�ش��Ľ�� (�ִ���array)
		*/
		function HttpRequest( $url, $method = "GET", $data = NULL, $additional_headers = NULL, $followRedirects = true )
		{
		    # in compliance with the RFC 2616 post data will not redirected
		   $method = strtoupper($method);
		   $url_parsed = @parse_url($url);
		   if (!@$url_parsed['scheme'])
		    $url_parsed = @parse_url('http://'.$url);
		
		   # debug��
		   $this->DebugOut($url_parsed);
		
		   # $url_parsed �� array չ���� $scheme, $host, $port, $user, $pass, $path, $query, $fragment
		    extract($url_parsed);
		
		   # �����ϱ���
		   $FormData = NULL;
		     if(is_array($data))
		    {
		          $ampersand = '';
		        $temp = NULL;
		    foreach($data as $k => $v)
		         {
		     if ($k != "0")
		     {
		         $temp .= $ampersand.urlencode($k).'='.urlencode($v);
		      $ampersand = '&';
		     }
		    }
		    $FormData = $temp;
		   }
		
		   # fix���ߵ�Port
		   if(!@$port)
		   {
		    if ($scheme == "https")
		     $port = 443;
		    else
		     $port = 80;
		   }
		
		   if (!@$path)
		    $path = '/';
		   if (($method == "GET") and (@$FormData))
		    $path .= (@$query) ? ("&".$FormData) : ("?".$FormData);
		
		   # debug�� 
		   $this->DebugOut($path); 
		
		   $out = "$method $path {$_SERVER['SERVER_PROTOCOL']}".CRLF;
		   $out .= "Host: $host".CRLF;
		
		   if ($method == "POST")
		   {
		    $out .= "Content-type: application/x-www-form-urlencoded".CRLF;
		    $out .= "Content-length: ".@strlen($FormData).CRLF;
		   }
		
		   if (@$additional_headers)
		   {
		    if (is_array( $this->requestHeaders) )
		    {
		     foreach( $this->requestHeaders as $k => $v )
		     {
		      $out .= "$k: $v".CRLF;
		     }
		    }
		    else
		    {
		     $out .= $additional_headers.CRLF;
		    }
		   }
		
		   $out .= "Connection: Close".CRLF.CRLF;
		
		   if ($method == "POST")
		    $out .= $FormData.CRLF;
		
		   # debug��
		   $this->DebugOut($out);
		
		   # https ��ַ��������
		   $RemoteHost = ($scheme == "https" ? "ssl://" : "").$host;
		
		   # Get the IP address for the target host.
		   $address = gethostbyname($host);
		
		   # debug��
		   $this->DebugOut("fsockopen() Connect to Host:".$RemoteHost." (IP:".$address.") Port:".$port);
		
		   if(!$fp = @fsockopen($RemoteHost, $port, $errno, $errstr, 15))
		   {
		    # debug��
		    $this->DebugOut("fsockopen() Connection Error. Error($errno) $errstr\n");
		
		    return false;
		   }
		   fwrite($fp, $out);
		
		   $foundBody = false;
		   $header = "";
		   $body = "";
		
		   while (!feof($fp))
		   {
		    $s = fgets($fp, 4096);
		
		    # debug��
		    $this->DebugOut($s);
		
		    if ( $s == CRLF )
		    {
		     $foundBody = true;
		     continue;
		    }
		    if ( $foundBody )
		    {
		     $body .= $s;
		    }
		    else
		    {
		
		     #echo $s;
		
		     if( ($followRedirects) and (preg_match('/^Location:(.*)/i', $s, $matches) != false) )
		     {
		      fclose($fp);
		      if (strpos(trim($matches[1]), 'http') === false)
		      {
		       $urlTo = trim($matches[1]);
		       $urlLocation = ($scheme == 'https' ? 'https://' : 'http://').($host).(substr($urlTo, 0, 1) == '/' ? '' : '/').($urlTo);
		       return $this->HttpRequest( $urlLocation );
		      }
		      else
		       return $this->HttpRequest( trim($matches[1]) );
		     }
		     $header .= $s;
		     if(preg_match('@HTTP[/]1[.][01x][\s]{1,}([1-5][01][0-9])[\s].*$@', $s, $matches))
		     {
		      $status = trim($matches[1]);
		     }
		    }
		   }
		   fclose($fp);
		   return array('head' => trim($header), 'body' => trim($body), 'status' => $status);
		}
		
		
		/*
		   ��;:
		    Http Response Header Fields�����
		
		   ��������ֵ:
		    $header = Http Response Header
		    ��
		     HTTP/1.1 200 OK
		     Date: Tue, 23 Jan 2007 05:41:08 GMT
		     Server: Apache/2.0.54 (Unix) DAV/2
		     Last-Modified: Fri, 30 Jun 2006 07:16:29 GMT
		     ETag: "28401-1f-d3c9cd40"
		     Accept-Ranges: bytes
		     Content-Length: 31
		     Connection: close
		     Content-Type: text/html
		
		   ��������ֵ:
		    ����ַ�ش��Ľ�� (�ִ���array)
		*/
		function parse_header($header)
		{
		   $out = NULL;
		   $header_data = preg_split('/\r\n/',$header);
		
		   if (is_array($header_data))
		   {
		    foreach($header_data as $k => $v)
		         {
		     $data = preg_split('/: /',$v);
		     $out[$data[0]] = $data[1];
		    }
		   }
		   return $out;
		}
		
		
		
		function GetStatusText($status)
		{
		   switch ((int)$status)
		   {
		    case 100:
		     $str = "Continue";
		     break;
		    case 101:
		     $str = "Switching Protocols";
		     break;
		    case 200:
		     $str = "OK";
		     break;
		    case 201:
		     $str = "Created";
		     break;
		    case 202:
		     $str = "Accepted";
		     break;
		    case 203:
		     $str = "Non-Authoritative Information";
		     break;
		    case 204:
		     $str = "No Content";
		     break;
		    case 205:
		     $str = "Reset Content";
		     break;
		    case 206:
		     $str = "Partial Content";
		     break;
		    case 300:
		     $str = "Multiple Choices";
		     break;
		    case 301:
		     $str = "Moved Permanently";
		     break;
		    case 302:
		     $str = "Found";
		     break;
		    case 303:
		     $str = "See Other";
		     break;
		    case 304:
		     $str = "Not Modified";
		     break;
		    case 305:
		     $str = "Use Proxy";
		     break;
		    case 307:
		     $str = "Temporary Redirect";
		     break;
		    case 400:
		     $str = "Bad Request";
		     break;
		    case 401:
		     $str = "Unauthorized";
		     break;
		    case 402:
		     $str = "Payment Required";
		     break;
		    case 403:
		     $str = "Forbidden";
		     break;
		    case 404:
		     $str = "Not Found";
		     break;
		    case 405:
		     $str = "Method Not Allowed";
		     break;
		    case 406:
		     $str = "Not Acceptable";
		     break;
		    case 407:
		     $str = "Proxy Authentication Required";
		     break;
		    case 408:
		     $str = "Request Time-out";
		     break;
		    case 409:
		     $str = "Conflict";
		     break;
		    case 410:
		     $str = "Gone";
		     break;
		    case 411:
		     $str = "Length Required";
		     break;
		    case 412:
		     $str = "Precondition Failed";
		     break;
		    case 413:
		     $str = "Request Entity Too Large";
		     break;
		    case 414:
		     $str = "Request-URI Too Large";
		     break;
		    case 415:
		     $str = "Unsupported Media Type";
		     break;
		    case 416:
		     $str = "Requested range not satisfiable";
		     break;
		    case 417:
		     $str = "Expectation Failed";
		     break;
		    case 500:
		     $str = "Internal Server Error";
		     break;
		    case 501:
		     $str = "Not Implemented";
		     break;
		    case 502:
		     $str = "Bad Gateway";
		     break;
		    case 503:
		     $str = "Service Unavailable";
		     break;
		    case 504:
		     $str = "Gateway Time-out";
		     break;
		     case 505:
		     $str = "HTTP Version not supported";
		     break;
		    default:
		     $str = "Unknow status ".$ststus;
		     break;
		   }
		   return $str;
		}
}

?>

