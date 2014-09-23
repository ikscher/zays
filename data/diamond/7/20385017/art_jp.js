
var jp = {
	sound_step:5,//增减音量步长
	mousePos:{x:0,y:0},//鼠标坐标
	sound_bar:{bar_left:0,mouse_left:0,state:0,length:80},
	play_bar:{bar_left:0,mouse_left:0,state:0,length:613},
	list:[],
	player:'',
	is_paly_bar_run:true,//是否随播放进度改变进度条
	
	init:function(){
		$("#jpId").jPlayer( {
			ready: function () {
			  this.element.jPlayer("setFile", ""); // Defines the mp3
			  jp.playMp3(0);
			}
		});
		
		jp.player=$("#jpId");
		
		$("#pre_btn").mouseover(function(){	//上一曲
			$(this).css("background-position", "1px 1px");
		}).mouseout(function(){
			$(this).css("background-position", "0px 0px");
		}).click(function(){
			jp.playMp3(jp.now_play_id-1);
		}).next().mouseover(function(){		//播放-暂停按钮
			$(this).css("background-position", "1px 1px");
		}).mouseout(function(){
			$(this).css("background-position", "0px 0px");
		}).click(function(){
			if( $(this).attr("class") == "start_btn" ){
				$(this).attr("class", "suspend_btn");
				jp.player.jPlayer('play')
			}else{
				$(this).attr("class", "start_btn");
				jp.player.jPlayer('pause')
			}
		}).next().mouseover(function(){		//停止按钮
			$(this).css("background-position", "1px 1px");
		}).mouseout(function(){
			$(this).css("background-position", "0px 0px");
		}).click(function(){
			jp.player.jPlayer('stop');
		}).next().mouseover(function(){		//下一曲
			$(this).css("background-position", "1px 1px");
		}).mouseout(function(){
			$(this).css("background-position", "0px 0px");
		}).click(function(){
			jp.playMp3(jp.now_play_id+1);
		});
		
		
		jp.ob_sound_bar = $("#icon_volume_loading").mousedown(function(){
			jp.sound_bar.bar_left = $(this).position().left;
			jp.sound_bar.mouse_left = jp.mousePos.x;
			jp.sound_bar.state = 1;
			
		});
		
		$("#icon_volume_min").click(function(){ //减音量
			jp.setSound(jp.ob_sound_bar.position().left-jp.sound_step);
		}).next().next().click(function(){   //增音量
			jp.setSound(jp.ob_sound_bar.position().left+jp.sound_step);
		});
		jp.ob_sound_bar.parent().click(function(){
			var left = jp.mousePos.x-$(this).offset().left-jp.ob_sound_bar.width()/2;
			jp.setSound(left);
		});
		
		jp.ob_play_bar = $("#loading").mousedown(function(){
			jp.play_bar.bar_left = $(this).position().left;
			jp.play_bar.mouse_left = jp.mousePos.x;
			jp.play_bar.state = 1;
		}).mouseover(function(){
			jp.is_paly_bar_run = false;
		}).mouseout(function(){
			jp.is_paly_bar_run = true;
		});
	
		
		$("body").live("mousemove",function(e){
			var e = e || window.event;
			jp.mousePos = { 
				x:e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft,
				y:e.clientY + document.body.scrollTop + document.documentElement.scrollTop 
			};
			if( jp.sound_bar.state == 1 ){
				var left = jp.sound_bar.bar_left + jp.mousePos.x - jp.sound_bar.mouse_left;
				jp.setSound(left);
			}
			if( jp.play_bar.state == 1 ){
				var left = jp.play_bar.bar_left + jp.mousePos.x - jp.play_bar.mouse_left;
				if( left <= jp.play_bar.length && left >= 0 ){
					jp.setPlay(left);
				}
			}
			
			
			$("#show").text( jp.mousePos.x + "--" + jp.mousePos.y );
		}).mouseup(function(){
			jp.sound_bar.state = 0;
			jp.play_bar.state = 0;
		});
		
		jp.mp3List();
		
		$("#mp3_list tr:gt(0)").mouseover(function(){
			$(this).attr("bgColor","#FEF0F1");
		}).mouseout(function(){
			$(this).attr("bgColor","");
		}).click(function(){
			var id = $(this).attr("id").split("_")[1];
			jp.playMp3(id);
		});
		
		jp.player.jPlayer("onProgressChange", function(lp,ppr,ppa,pt,tt) {
			$("#loading_buffer").css("width", parseInt(626*lp/100)+"px" );	//缓冲进度条
			if( jp.is_paly_bar_run && jp.sound_bar.state == 0 ){
				jp.setPlay( parseInt(jp.play_bar.length*ppa/100), 'ok' );		//播放进度条
			}
			$("#player_time").text($.jPlayer.convertTime(pt));//时间显示
			
			/* var s = '缓冲百分比:'+lp +'% <br />';
			s += '已播放占已缓冲的百分比:'+ppr +'% <br />';
			s += '已播放占总时长的百分比:'+ppa +'%<br />';
			s += '时间:'+$.jPlayer.convertTime(pt)+ ' / '+$.jPlayer.convertTime(tt);
			$("#play_info1").html(s);  */
		}).jPlayer("onSoundComplete", function() {//播放结束了;
			jp.playMp3(jp.now_play_id+1);
		});;
		
		
	},
	
	//按id播放歌曲
	playMp3:function(id){
		if( typeof jp.list[id] == 'object' ){
			jp.now_play_id = parseInt(id);
			$("#player_title").text(jp.list[id].title);
			jp.player.jPlayer("setFile", jp.list[id].path );
			jp.player.jPlayer('play');
			$("#start_btn").attr("class", "suspend_btn");
		}
	},
	
	//显示歌曲列表
	mp3List:function(){
		var s = '';
		for( var i in jp.list){
			var item = jp.list[i];
			s += '\
							<tr id="m_'+i+'">\
								<td id="m_'+i+'">'+item.title+'</td>\
								<td>'+username+'</td>\
							</tr>\
			';
		}
		s = '\
						<table cellspacing="0" cellpadding="5" border="0">\
							<tr class="td_title">\
								<td>歌曲名</td>\
								<td>歌手名</td>\
							</tr>'+s+'\
						</table>\
		';
		$("#mp3_list").html(s);
	},
	
	//设定音量
	setSound:function(left){
		if( left <= jp.sound_bar.length && left >= 0 ){
			jp.ob_sound_bar.css("left",left+"px");
			jp.player.jPlayer('volume', parseInt( left/jp.sound_bar.length*100 ));
		}
	},
	
	//设定进度条位置
	setPlay:function(left, flag){
		if( left <= jp.play_bar.length && left >= 0 ){
			jp.ob_play_bar.css("left",left+"px");
			jp.ob_play_bar.prev().css("width",left+"px");
			if( !flag )	jp.player.jPlayer('playHead', parseInt( left/jp.play_bar.length*100 ));
		}
	}
	
}
$(document).ready(function(){
	jp.init(); 
});