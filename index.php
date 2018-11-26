<?php
    //获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = 'ying';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }else{

        //1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        $postObj = simplexml_load_string( $postArr );
       
        switch (strtolower( $postObj->MsgType)){
                case 'event':
                    //判断具体的时间类型（关注、取消、点击）
            		$event = strtolower($postObj->Event); 
                    if ($event=='subscribe') { // 关注事件
                      $toUser   = $postObj->FromUserName;
                      $fromUser = $postObj->ToUserName;
                      $time     = time();
                      $msgType  =  'text';
                      $content  = '欢迎关注我们的微信公众账号hhhh';
                      $template = "<xml>
                      			<ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                      $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                      echo $info;
                      }
                case 'text'://文本消息
            			$content=$postObj->Content;
            			$toUser   = $postObj->FromUserName;
                        $fromUser = $postObj->ToUserName;
                		$time     = time();
               	 		$msgType  =  'text';
            			$content=explode('天气', $content);
                		$content  = $content[0];
            			if($content=='北京')
                          $content  = '北京天气：
										天气现象：多云 
										风向：西南风
										风力：<3级 
										最高气温：10 ';
            			if($content=='西安')
                          $content  = '西安天气：
										天气现象：阴 
										风向：西南风
										风力：<2级 
										最高气温：8 ';
            			if($content=='咸阳')
                          $content  = '咸阳天气：
										天气现象：大雨 
										风向：西南风
										风力：<5级 
										最高气温：5 ';
                		$template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                		$info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                		echo $info;
                    break;
         
            }        

    }
