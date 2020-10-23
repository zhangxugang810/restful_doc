<div class="rbox">
    <h1>代码注释说明</h1>
    <div class="rb">
        <h2>一、类注释字段说明</h2>
        <div>
            <ul>
                <li>
                    <h3>1.@classname:</h3>
                    <div>类名称，请注意在这里的类名称实际上是接口类的汉语类名称，请不要直接将类名写在这里。</div>
                </li>
                <li>
                    <h3>2.@classaccess [public|private]:</h3>
                    <div>
                        是否允许本文档系统进行解析并显示在接口文档中。<br />
                        publice是允许解析，设置为public的话文档中就会显示该类的名称@classname
                        private是不允许解析，设置为private那么这个类就不会再接口文档列表中展示
                    </h3>
                </li>
                <li>
                    <h3>3.@decription:</h3>
                    <div>类的详细描述，该字段目前没有使用，它是对类名称进行更加详细的描述的字段，文档系统不排除在以后的版本中有启用该字段的可能，所以建议你不要省略。</div>
                </li>
                <li>
                    <h3>4.@author:</h3>
                    <div>本类的作者（建立者），该字段目前没有使用，它是用来记录类的创作人的字段，文档系统不排除在以后的版本中有启用该字段的可能，所以建议你不要省略。</div>
                </li>
                <li>
                    <h3>5.@updateTime:</h3>
                    <div>最后更新时间，该字段目前没有使用，它是用来记录类最后一次修改的时间，文档系统不排除在以后的版本中有启用该字段的可能，所以建议你不要省略。</div>
                </li>
                <li>
                    <h3>6.@errorCode:</h3>
                    <div>错误代码说明,在这里你可以设置多个错误代码，错误代码会在每一个接口中显示。</div>
                </li>
                <li>
                    <h3>7.实例(如图所示)</h3>
                    <div>
                        <img src="<?php echo IMAGE_PATH.'/eg1.png'?>" />
                    </div>
                </li>
            </ul>
        </div>
        <h2>二、接口注释字段说明</h2>
        <div>
            <ul>
                <li>
                    <h3>1.@see:</h3>
                    <div>接口的汉字名称，请不要直接将方法名写在这里，这个字段请使用尽量稍等额汉字进行描述，因为这个字段是要显示在列表中的，不能使用太多的汉字，如果少量汉字不能描述清楚的请在@describe中做更加详细的描述</div>
                </li>
                <li>
                    <h3>2.@describe:</h3>
                    <div>接口的详细说明，在这里你可以写更多的文字用来把你的接口描述的更清楚，可以作为字段@see的补充说明。</div>
                </li>
                <li>
                    <h3>3.@access[public/private/protected]:</h3>
                    <div>
                        接口解析权限，在这里可以设置接口是否在文档中显示；<br />
                        public：允许解析，设置为public则该方法会在接口文档中显示。<br />
                        private：不允许解析，设置为private则该方法不会在接口文档中显示。<br />
                        protected：不允许解析，设置为protected则该方法不会在接口文档中显示。<br />
                    </div>
                </li>
                <li>
                    <h3>4.@name:</h3>
                    <div>接口的名称，即接口的调用名称，这个字段和本系统的项目设置可以形成完整的项目接口地址，</div>
                </li>
                <li>
                    <h3>5.@method:[post/get/put/delete/options]</h3>
                    <div>提交参数方法，本字段定义该接口通过post还是get还是其他方法来接收参数，默认为GET方法，如果接口不需要提交参数，可以省略该字段。</div>
                </li>
                <li>
                    <h3>6.@requestType:[FORM/JSON]</h3>
                    <div>提交数据类型，目前仅支持FORM/JSON字符串，如果选择FORM则采用key=value的方式提交参数,如果采用JSON则提交的是JSON字符串（非JSON字符串也可以）。</div>
                </li>
                <li>
                    <h3>7.@notice:</h3>
                    <div>接口注意事项，在这里可以提示一些文档中的未尽事宜，如：某个参数应该特别注意一些什么等。</div>
                </li>
                <li>
                    <h3>8.@datanotice:</h3>
                    <div>外联文档，这个参数是为您计入其他站点的链接提供方便，参数形式：动态标示名称|url|高度（缺省值：300）|宽度（缺省值：100%）。</div>
                </li>
                <li>
                    <h3>9.@author:</h3>
                    <div>作者和版本信息，参数设置形式：姓名，时间，修改原因（如：创建），如： XXX,2015年12月08日,创建|XXX,2015年12月08日,测试出Bug，这个参数是方法的更改记录和版本信息记录。</div>
                </li>
                <li>
                    <h3>10.@example [str/param(content)]:</h3>
                    <div>接口参数实例，这里提供POST或GET参数的实例，如果是JSON字符串选择str，如果是FORM选择param(paramName:param|paramName:param)，对于复杂的参数可以在这里写出一个可供调试的例子供用户调试使用</div>
                </li>
                <li>
                    <h3>11.@param:</h3>
                    <div>接口参数说明，形式如：参数名称|参数类型|是否必填[required/unrequired]|参数说明|补充说明，例如：@param picid|int|required|图片id|说明|text。</div>
                </li>
                <li>
                    <h3>12.@cookie:</h3>
                    <div>设定COOKIE名字，并把结果用保存在cookie，这个参数是为了支持之后的开发而是指，暂时没有用处可以忽略不计。</div>
                </li>
                <li>
                    <h3>13.@header:</h3>
                    <div>在header头里传递的参数，形式如：字段名|参数类型|是否必选[required/unrequired]|字段说明|默认值，例如：@header access-token|string|required|渠道鉴权凭证|在渠道鉴权获得|aaa。</div>
                </li>
                <li>
                    <h3>14.@baseauth:</h3>
                    <div>HTTP协议里支持的基本认证，形式如：需要[yes/no]|默认用户名|默认密码，例如：yes|aaa|bbb, 如果不需要认证也可删除改行。</div>
                </li>
                <li>
                    <h3>15.@return:</h3>
                    <div>
                        返回参数说明，这个参数的形式如下：<br />
                        a.返回为空字符串时的设置格式:@return |null|<br />
                        b.当返回的数据没有键只有值时的设置格式：@return |int|参数描述<br />
                        c.正常返回参数时的设置格式：参数名|参数类型|参数描述（例如：@return user_id|int|用户id）<br />
                        <!--d.当返回参数类型为table时的设置方式：返回参数名|表字段类型|表名|字段（例如：@return users|table|users|*），请注意该字段要求本系统必须能够你读取您的数据库才可以使用，否则请不要试着这样的返回字段-->
                        d.当返回参数类型为数组（array）是的设置方式如下：返回参数名|数组类型|参数说明（例如：@return users|array|用户信息），对于数组内部的说明设置如下：<br />
                            @return_数组参数名 参数名|参数类型|参数描述（例如：@return_users id|int|编号 @return_users pics|array|相册），当数组的参数也是数组时，设置方式如下：<br />
                            @return_数组参数名_数组参数名 参数名|参数类型|参数描述(例如：@return_users_pics path|string|相片路径)，以此类推，系统会递归的显示n级数组的参数设置。<br />
                    </div>
                </li>
                <li>
                    <h3>16.实例（如果所示）:</h3>
                    <div><img src="<?php echo IMAGE_PATH.'/eg2.png'?>" /></div>
                </li>
            </ul>
        </div>
    </div>
    <?php
    /*<h1>代码注释说明</h1>
    <div class="rb">
        <h3 class="h3">2.接口注释说明：</h3>
        <div>
            所有接口的注释都应该严格按照以下格式及要求撰写。<br/>
            /**<br/>
            &nbsp;&nbsp;* @see                                 //函数描述，描述函数一行说明：，描述用一行就够了，不能超过一行。<br/>
            &nbsp;&nbsp;* @describe                            //详细说明
            &nbsp;&nbsp;* @access public/private/protected     //接口调用权限<br/>
            &nbsp;&nbsp;* @name delPic                         //函数名称<br/>
            &nbsp;&nbsp;* @method POST                         //传参类型，如果不需要传参数，此行省略<br/>
            &nbsp;&nbsp;* @requestType FORM/JSON               //数据提交方式<br/>
            &nbsp;&nbsp;* @notice 一定要传id哦                  //注意事项<br/>
            &nbsp;&nbsp;* @datanotice 动态标示名称|url|高度（缺省值：300）|宽度（缺省值：100%）          //外联文档：外联文档名称|要显示的网址（相对路径）|宽度|高度<br/>
            &nbsp;&nbsp;* @author XXX,2015年12月08日,创建|XXX,2015年12月08日,测试原因<br/>
            &nbsp;&nbsp;* @example str/param(content) 示例参数类型(示例参数内容：JSON字符串，或者按照下面格式的字符串：AppId:258f9d3d051d64|AppSecret:ZGUwMDAxYTRjMTM1ZDNjNDE4OWJmYzU0N2QwODBkNjI)对于复杂的参数可以在这里写出一个可供调试的例子供用户调试使用<br/>
            &nbsp;&nbsp;* @param picid|int|required|图片id|说明|text             //字段名|类型|必选|字段说明|默认是不填的，如果需要的是大数据传输就填“text”<br/>
            &nbsp;&nbsp;* @param picname|string|unrequired|图片名称|说明      //字段名|类型|非必选|字段说明<br/>
            &nbsp;&nbsp;* @example                                           //<br/>
            &nbsp;&nbsp;* @cookie cookiename                                 //要保存的本次结果名称，同时也是cookie的名称<br/>
            &nbsp;&nbsp;* @header picid|int|required|图片id|说明|text             //字段名|类型|必选|字段说明|默认值，如果需要的是大数据传输就填“text”<br/>
            &nbsp;&nbsp;* @header picname|string|unrequired|图片名称|说明|text      //字段名|类型|非必选|字段说明|默认值<br/>
            &nbsp;&nbsp;* @baseauth yes|username|password //需要|默认用户名|默认密码 //yes需要，如不需要请删除改行<br/>
            &nbsp;&nbsp;* @return |null|    //当返回的数据为空字符串时，用此格式<br/>
            &nbsp;&nbsp;* @return |int|编号         //   |类型|参数说明   当返回的数据没有键，只有值时，用此格式<br/>
            &nbsp;&nbsp;* @return user_id|int|用户id          //返回参数名|类型|参数说明<br/>
            &nbsp;&nbsp;* @return users|table|users|*        //返回参数名|表字段类型|表名|字段     如果本系统已经连接到您的数据库，则对于返回的数组元素均为某个表的字段的情况，可以直接注明表名和要返回的字段名。<br/>
            &nbsp;&nbsp;* @return users|array|users|*        //返回参数名|数组类型|参数说明       对于返回的数组元素需要逐个描述的情况<br/>
            &nbsp;&nbsp;* @return_users id|int|编号          //users数组元素字段名|类型|参数说明     返回的users数组的元素id<br/>
            &nbsp;&nbsp;* @return_users pics|array|相册        //users数组元素字段名|类型|参数说明     返回的users数组的元素pics,注意此时pics也是一个数组，<br/>
            &nbsp;&nbsp;* @return_users_pics path|string|相片路径     //pics数组元素字段名|类型|参数说明    返回的users数组的元素pics,此时数组pics中的元素path的表示方法，<br/>
            &nbsp;&nbsp;<br/>
        </div>
    </div>*/
    ?>
</div>