<html>
    <head>
        <style>
            *{
                margin: 10px 15px;
                font-size: 15px;
            }
            .container{
                width:1000px;
                margin:auto auto;
                background-color: #d7d7d7;
                border-radius: 10px;
            }
            .title{
                line-height: 30px;
                padding-bottom: 5px;
                padding-top: 10px;
                font-size: 18px;
                margin-top: 20px;
                margin-bottom: 10px;
                border-bottom: solid 1px #fff;
            }
            input,select{
                border-radius: 2px;
            }
            .name input{
                width: 100px;
            }
            .label input{
                width: 100px;
            }
            .default input{
                width: 50px;
            }
            .desc input{
                width: 200px;
            }
            .required input{
                width: 20px;
            }
            .submit{
                width: 200px;
                background-color: #8f8f94;
            }
            .button{
                padding:2px 5px;
                line-height: 40px;
                background-color: #fff;
                text-align: center;
                display: inline;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <form method="post" action="/interface/add" onsubmit="return upload();">
                <h3 class="title">接口信息</h3>
                <table class="table table-bordered" id="interface">
                </table>
                <h3 class="title">请求参数</h3>
                <table class="table table-bordered">
                    <thead>
                        <th>参数名</th>
                        <th>类型</th>
                        <th>标签</th>
                        <th>默认值</th>
                        <th>注释</th>
                        <th>必须</th>
                        <th><div class="button param-add" onclick="addRequestGroup()">+</div></th>
                    </thead>
                    <tbody id="request">
                    </tbody>
                </table>
                <h3 class="title">响应数据</h3>
                <table class="table table-bordered">
                    <thead>
                        <th></th>
                        <th>参数名</th>
                        <th>类型</th>
                        <th>标签</th>
                        <th><div class="button param-add" onclick="addResponseGroup()">+</div></th>
                    </thead>
                    <tbody id="response">
                    </tbody>
                </table>
                <table>
                    <tr>
                        <td><button class="submit" type="submit">提交</button></td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="test"></div>
        <?= \yii\helpers\Html::jsFile('/resource/jquery.min.js')?>
        <?= \yii\helpers\Html::jsFile('/resource/interface.js')?>
    <script type="text/javascript">
        function getCreator(opt,data) {
            data=typeof data =='object'?data:{};
            var value=typeof data[opt.name] == 'undefined'?opt.value:data[opt.name];
            switch (opt.type){
                case 'text':
                    return _self.creatorFactory.getTextCreator().setName(opt.name).create().setValue(value);
                case 'select':
                    return _self.creatorFactory.getSelectCreator().setName(opt.name).setSelectOpt(opt.opt).create().setValue(value);
                case 'radio':
                    var creator;
                    var group=_self.creatorFactory.getRadioGroup(opt.name);
                    var radioName=opt.getName(opt.name);
                    for(var key in opt.opt){
                        creator=_self.creatorFactory.getRadioCreator();
                        creator.setName(radioName).setLabel(opt.opt[key]).create().setValue(key);
                        group.add(creator);
                    }
                    group.setValue(value);
                    return group;
                case 'checkbox':
                    var creator;
                    var group=_self.creatorFactory.getCheckBoxGroup(opt.name);
                    var checkBoxName=opt.getName();
                    for(var key in opt.opt){
                        creator=_self.creatorFactory.getRadioCreator();
                        creator.setName(checkBoxName).setLabel(opt.opt[key]).create().setValue(key);
                        group.add(creator);
                    }
                    group.setValue(value);
                    return group;
                default:
                    throw new EventException('not found <'+type+'>creator');
            }
        }
        function ParamGroup() {
            this.__item=[];
            this.__oldData={};
        }
        ParamGroup.prototype={
            add:function (item) {
                this.__item.push(item);
                return this;
            },
            getValue:function () {
                var res={};
                var item;
                for(var key in this.__item){
                    item=this.__item[key];
                    res[item.getName()]=item.getValue();
                }
                return $.extend({},this.__oldData,res);
            },
            setOldData:function (data) {
                this.__oldData=data;
                return this;
            }
        }
        function RequestParamManager() {
            this.__groups=[];
        }
        RequestParamManager.prototype={
            add:function (opt,data) {
                return (new RequestParamGroupCreator(this)).create(opt,data);
            },
            addGroup:function (item) {
                return this.__groups.push(item)-1;
            },
            getValue:function () {
                var res=[];
                var item;
                for(var key in this.__groups){
                    item=this.__groups[key].group;
                    if(typeof item !='undefined')res.push(item.getValue());
                }
                return res;
            },
            remove:function(index) {
                this.__groups[index].remove();
                delete this.__groups[index];
            }
        }
        var getRadioName=function () {
            var index=0;
            return function (pre) {
                index++;
                return pre+'_'+index;
            }
        }();
        function RequestParamGroupCreator(manager) {
            this._tr;
            this.group;
            this._index;
            this.__manager=manager;
            function createPlusBtn(group) {
                var btn=$('<div class="button param-plus">-</div>');
                var td=$('<td>');
                btn.appendTo(td);
                group._tr.append(td);
                btn.click(function () {
                    group.remove();
                })
            }
            this.create=function (opt,data) {
                data=typeof data == 'object'?data:{};
                this._index=this.__manager.addGroup(this);
                this.group=new ParamGroup();
                this.group.setOldData(data);
                this._tr=$('<tr>');
                var creator;
                var td;
                for (var key in opt){
                    td=$('<td>');
                    td.addClass(opt[key].name);
                    td.appendTo(this._tr);
                    creator=getCreator(opt[key],data);
                    this.group.add(creator);
                    creator.appendTo(td);
                }
                createPlusBtn(this);
                return this;
            }
        }
        RequestParamGroupCreator.prototype={
            remove:function () {
                if(this._tr instanceof jQuery)this._tr.remove();
                delete this.group;
            },
            appendTo:function (parent) {
                if(this._tr instanceof jQuery)this._tr.appendTo(parent);
            }
        }

        function ResponseParamCreator(parent){
            this.__floor=0;
            this.group;
            this.__children=[];
            this.__parent=parent;
            this.__dom=$('<tr>');
            this.__index;
            if(parent instanceof ResponseParamCreator)this.__floor=parent.getFloor()+1;
            function createBtn(response,opt) {
                var td=$('<td>');
                td.appendTo(response.getDom());
                createAddBtn(response,opt).appendTo(td);
                createPlusBtn(response).appendTo(td);
            }
            function createPlusBtn(response) {
                var btn=$('<div class="button param-plus">-</div>');
                btn.click(function () {
                    response.remove();
                })
                return btn;
            }
            function createAddBtn(response,opt){
                var btn=$('<div class="button param-add">+</div>');
                btn.click(function () {
                    response.addChild(opt,{});
                })
                return btn;
            }
            this.create=function (opt,data) {
                data=typeof data == 'object'?data:{};
                this.group=new ParamGroup();
                this.group.setOldData(data);
                var creator;
                var td=$('<td>');
                var text='';
                for(var key=0;key<=this.__floor;key++){
                    text+='>';
                }
                td.text(text);
                td.appendTo(this.getDom());
                for (var key in opt){
                    td=$('<td>');
                    td.addClass(opt[key].name);
                    td.appendTo(this.getDom());
                    creator=getCreator(opt[key],data);
                    this.group.add(creator);
                    creator.appendTo(td);
                }
                createBtn(this,opt);
                this.__parent.appendChildDom(this);
                return this;
            }
            this.getFloor=function () {
                return this.__floor;
            }

            this.createChildren=function(opt,children){
                for(var key in children){
                    this.addChild(opt,children[key]);
                }
                return this;
            }
        }
        ResponseParamCreator.prototype={
            addChild:function (opt,data) {
                var child=new ResponseParamCreator(this);
                child.create(opt,data);
                child.setIndex(this.__children.push(child)-1);
                if(typeof data == 'object' && data.children instanceof Array)child.createChildren(opt,data.children);
                return child;
            },
            getLastChild:function () {
                var lastChild=this.__children[this.__children.length-1];
                if(lastChild instanceof ResponseParamCreator){
                    return lastChild.getLastChild();
                }else{
                    return this;
                }
            },
            removeChild:function (index) {
                delete this.__children[index];
                return this;
            },
            remove:function () {
                for(var key in this.__children){
                    this.__children[key].remove();
                }
                this.__dom.remove();
                this.__parent.removeChild(this.__index);
                return true;
            },
            getIndex:function () {
                return this.__index;
            },
            setIndex:function (index) {
                this.__index=index;
                return this;
            },
            getDom:function () {
                return this.__dom;
            },
            appendChildDom:function (child) {
                var lastChild=this.getLastChild();
                lastChild.getDom().after(child.getDom());
                return this;
            },
            getValue:function () {
                var value=this.group.getValue();
                var children=[];
                var item;
                for(var key in this.__children){
                    item=this.__children[key];
                    if(item instanceof ResponseParamCreator)children.push(item.getValue());
                }
                if(children.length>0)value['children']=children;
                return value;
            }
        }
        function ResponseParamManager() {
            this.__groups=[];
            this.__floor=-1;
            this.__dom=$('#response');
            this.getFloor=function () {
                return this.__floor;
            }
        }
        ResponseParamManager.prototype={
            add:function (opt,data) {
                var creator=(new ResponseParamCreator(this)).create(opt,data);
                creator.setIndex(this.__groups.push(creator)-1).getDom().appendTo(this.__dom);
                if(typeof data == 'object' && data.children instanceof Array)creator.createChildren(opt,data['children']);
                return creator;
            },
            getValue:function () {
                var res=[];
                var item;
                for(var key in this.__groups){
                    item=this.__groups[key];
                    if(item instanceof ResponseParamCreator)res.push(item.getValue());
                }
                return res;
            },
            removeChild:function(index) {
                delete this.__groups[index];
                return this;
            },
            appendChildDom:function (child) {
                this.__dom.append(child.getDom());
            }
        }
        function InterfaceInfo() {
            this.__place=$('#interface');
            this.group=new ParamGroup();
            function makeLabel(label) {
                var td=$('<td>');
                td.text(label);
                return td;
            }
            function makeInput(interface,opt,data) {
                var td=$('<td>');
                var creator=getCreator(opt,data);
                interface.group.add(creator);
                creator.appendTo(td);
                return td;
            }
            this.add=function (label,opt,data) {
                data=typeof data=='object'?data:{};
                this.group.setOldData(data);
                var tr=$('<tr>');
                tr.append(makeLabel(label));
                tr.append(makeInput(this,opt,data));
                this.__place.append(tr);
            }
        }
        InterfaceInfo.prototype={
            getValue:function () {
                return this.group.getValue();
            }
        }
        var requestManager=new RequestParamManager();
        var responseManager=new ResponseParamManager();
        var interfaceManager=new InterfaceInfo();
        var interfaceOpt=[
            {"label":'名称',input:{name:'description',type:'text',value:''}},
            {"label":'地址',input:{name:'url',type:'text',value:''}},
            {"label":'版本',input:{name:'version',type:'text',value:''}}
        ];
        var requestOpt=[
            {name:'name',type:'text',value:''},
            {name:'type',type:'select',opt:{'string':'字符串','array':'数组','int':'整型','float':'浮点型','object':'对象','boolean':'布尔'},value:'int'},
            {name:'label',type:'text',value:''},
            {name:'default',type:'text',value:''},
            {name:'description',type:'text',value:''},
            {name:'required',type:'radio',opt:{0:'否',1:'是'},'value':0,getName:getRadioName},
        ];
        var responseOpt=[
            {name:'name',type:'text',value:''},
            {name:'type',type:'select',opt:{'string':'字符串','array':'数组','int':'整型','float':'浮点型','object':'对象','boolean':'布尔'},value:'int'},
            {name:'label',type:'text',value:''},
        ];
        var interface={id:5,'description':'人员同步',url:'common/person/student','version':'2.0.1'};
        var responseData=[
            {id:7,'name':'success',type:'int',label:'0 失败 1 成功'},
            {id:8,'name':'code',type:'int',label:'错误编码'},
            {'name':'msg',type:'string',label:'错误信息'},
            {'name':'results',type:'array',label:'结果数组',children:[
                {"name":'name','type':'string','label':'姓名'},
                {"name":'age','type':'int','label':'年龄'},
                {"name":'address','type':'string','label':'地址'}
            ]}
        ];
        var requestData=[
            {name:'hello','type':'string','label':'姓名','default':'哈哈','description':'介绍自己','required':'1'},
        ];
        function addInterfaceInfo(data) {
            for(var key in interfaceOpt){
                interfaceManager.add(interfaceOpt[key].label,interfaceOpt[key].input,data)
            }
        }
        function addRequestGroup(data) {
            requestManager.add(requestOpt,data).appendTo($('#request'));
        }
        function addMultipleRequestData(data) {
            for(var key in data){
                addRequestGroup(data[key]);
            }
        }
        function addResponseGroup(data) {
            responseManager.add(responseOpt,data);
        }
        function addMultipleResponseData(data) {
            for(var key in data){
                addResponseGroup(data[key]);
            }
        }
        addInterfaceInfo(interface);
        addMultipleRequestData(requestData);
        addMultipleResponseData(responseData);
        function upload() {
            var params={
                'interface':interfaceManager.getValue(),
                'request':requestManager.getValue(),
                'response':responseManager.getValue()
            };
            console.log(params);
            $.ajax({
                "url":'/interface/add',
                'type':'post',
                'dataType':'json',
                'data':params,
                'success':function (res) {

                }
            });
            return false;
        }
    </script>
    </body>
</html>