<?php
/**
 * @desc 综合业务平台--查询API
 * @author harvey
 * @since 2010-11-20
 *
 */
 error_reporting(E_ALL);
set_time_limit(0);
ini_set('soap.wsdl_cache_enabled',0);//禁止wsdl缓存
ini_set('soap.wsdl_cache_ttl',0); 
include_once 'module/myaccount/DES.php';

class SynPlatAPI {
	/**
	 * 取得数据
	 * @param string $type	查询类型
	 * @param string $param	查询参数
	 * @return string
	 */
	private $type='1A020201';

	
	function getData($param) {
		include_once 'module/myaccount/config.php';
	    // $wsdlURL = "http://gboss.id5.cn/services/QueryValidatorServices?wsdl";
		
		/*$partner = "hongzhiniang123";
		$partnerPW = "hongzhiniang123_4*ds6UOF";

		$Key = "12345678";
		$iv = "12345678";*/
		
		$DES = new DES ( $Key, $iv );
		
        //$wsdlURL = "http://gboss.id5.cn/services/QueryValidatorServices?wsdl";
        // echo $wsdlURL.'and'.$partner.'and'.$partnerPW;exit;
		try {
            //var_dump(file_get_contents($wsdlURL));exit;
			if(!@file_get_contents($wsdlURL)) {
                throw new SoapFault('Server', 'No WSDL found at ' . $wsdlURL);
		    } 
	        
			$soap = new SoapClient ( $wsdlURL);

		    //$soap->xml_encoding = 'UTF-8';
			
            //$client = new SoapClient($ws, array('proxy_host'     => "113.140.8.202",
                                            //'proxy_port'     => 9682));
		/*} catch ( Exception $e ) {
			return "Linkerror";*/
		}catch (SoapFault $fault){
            //return "Fault! code:".",".$fault->faultcode.", string: ".",".$fault->faultstring;exit;
            MooMessage("验证失败，请您重新验证@！", "index.php?n=myaccount&h=smsindex",'01');	
		}
		
		//var_dump ( $soap->__getTypes () );
		//todo 加密数据
		$partner = $DES->encrypt ( $partner );
		$partnerPW = $DES->encrypt ( $partnerPW );
		$type = $DES->encrypt ( $this->type );
		//先将中文转码
		$param = mb_convert_encoding ( $param, "GBK", "UTF-8" );
		$param = $DES->encrypt ( $param );
		$params = array ("userName_" => $partner, "password_" => $partnerPW, "type_" => $type, "param_" => $param );
		//请求查询
		$data = $soap->querySingle ( $params );
		
		// todo 解密数据
		$resultXML = $DES->decrypt ( $data->querySingleReturn );
		$resultXML = mb_convert_encoding ( $resultXML, "UTF-8", "GBK" );
		return $resultXML;
	}
	
	/**
	 * 格式化参数
	 * @param array $params	参数数组
	 * @return string
	 */
	function formatParam($queryType, $params) {
		include 'config.php';
		if (empty ( $supportClass [$queryType] )) {
			return - 1;
		}
		$keys = array ();
		$values = array ();
		foreach ( $params as $key => $value ) {
			$keys [] = $key;
			$values [] = strtoupper ( $value );
		}
		$param = str_replace ( $keys, $values, $supportClass [$queryType] );
		return $param;
	}
	
	/**
	 * 取得生日（由身份证号）
	 * @param int $id 身份证号
	 * @return string
	 */
	function getBirthDay($id) {
		switch (strlen ( $id )) {
			case 15 :
				$year = "19" . substr ( $id, 6, 2 );
				$month = substr ( $id, 8, 2 );
				$day = substr ( $id, 10, 2 );
			break;
			case 18 :
				$year = substr ( $id, 6, 4 );
				$month = substr ( $id, 10, 2 );
				$day = substr ( $id, 12, 2 );
			break;
		}
		$birthday = array ('year' => $year, 'month' => $month, 'day' => $day );
		return $birthday;
	}
	
	/**
	 * 取得性别（由身份证号）--可能不准
	 * @param int $id 身份证号
	 * @return string
	 */
	function getSex($id) {
		switch (strlen ( $id )) {
			case 15 :
				$sexCode = substr ( $id, 14, 1 );
			break;
			case 18 :
				$sexCode = substr ( $id, 16, 1 );
			break;
		}
		if ($sexCode % 2) {
			return "男";
		} else {
			return "女";
		}
	}
	
	/**
	 * 格式化数据
	 * @param string $type
	 * @param string $data
	 * @return array
	 */
	function formatData($type, $data) {
		switch ($type) {
			case "1A020201" :
				$detailInfo = $data ['policeCheckInfos'] ['policeCheckInfo'];
				$birthDay = $this->getBirthDay ( $detailInfo ['identitycard'] );
				$sex = $this->getSex ( $detailInfo ['identitycard'] );
				$info = array (
						'name' => $detailInfo ['name'], 
						'identitycard' => $detailInfo ['identitycard'], 
						'sex' => $sex, 
						'compStatus' => $detailInfo ['compStatus'], 
						'compResult' => $detailInfo ['compResult'], 
						'policeadd' => $detailInfo ['policeadd'], 
						//'checkPhoto' => $detailInfo ['checkPhoto'], 
						'birthDay' => $birthDay, 
						'idcOriCt2' => $detailInfo ['idcOriCt2'], 
						'resultStatus' => $detailInfo ['compStatus'] );
			break;
			default :
				$info = array (false );
			break;
		}
		return $info;
	}
	
	/**
	 * 
	 * xml节点解析
	 * @param unknown_type $inXmlset
	 * @param unknown_type $needle
	 */
	
	function getXmlValueByTag($inXmlset,$needle){
	    $tagValue="";
        $resource    =    xml_parser_create();//Create an XML parser
        xml_parse_into_struct($resource, $inXmlset, $outArray);// Parse XML data into an array structure
        xml_parser_free($resource);//Free an XML parser
       
        for($i=0;$i<count($outArray);$i++){
            if($outArray[$i]['tag']==strtoupper($needle)){
                $tagValue    =    $outArray[$i]['value'];
            }
        }
        return $tagValue;
    } 
}