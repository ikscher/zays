// JavaScript Document

function AgentLogin(sid,pwd){
	var conn = dbio.set_server("","","","");
	if(conn != 'access'){alert(conn);}

	//设置服务器参数
	MDCAgent.InitParaSrv("192.168.0.170", 5881 );
	var usernum = parseInt(sid)-1;//通道号
	var WorkerID = parseInt(sid)+800;
	//坐席登录
	var nRet;
	nRet = MDCAgent.AgentLogin( usernum, WorkerID, pwd );
	//alert(MDCAgent.GetLoginErrorStr(nRet));
} 

function call(tel){
	MDCAgent.ScreenDialOut(tel,"","");
}

function AgentLogout(){
	MDCAgent.AgentLogout();
	MDCAgent.AboutBox();
}

