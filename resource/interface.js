(function($,_){
    function Creator(type){
        this.__DOM;
        this.__NAME;
        this.__VALUE;
        this.create=function () {
            throw new DOMException('need rewrite <Creator.create> function');
        }
        this.get=function(){
            return this.__DOM;
        },
        this.setType=function (type) {
            this.__TYPE=type;
            return this;
        }
        this.getType=function () {
            return this.__TYPE;
        }
        this.setName=function (name) {
            this.__NAME=name;
            return this;
        }
        this.getName=function(){
            return this.__NAME;
        }
        this.setValue=function(value){
            this.__VALUE=value;
            this.syncDom();
            return this;
        }
        this.getValue=function(){
            this.syncValue();
            return this.__VALUE;
        }
        this.syncValue=function () {
            this.__VALUE=this.get().val();
            return this;
        }
        this.syncDom=function () {
            this.get().val(this.__VALUE);
            return this;
        }
        this.appendTo=function ($parent) {
            this.get().appendTo($parent);
            return this;
        }
        this.setType(type);
    }
    function SelectCreator() {
        this.__SELECT_OPT;
        Creator.call(this,'select');
        this._createOption=function(select){
            for(var key in this.__SELECT_OPT){
                var opt=$('<option>');
                opt.attr('value',key);
                opt.text(this.__SELECT_OPT[key]);
                select.append(opt);
            }
            return select;
        }
        this.create=function(){
            var select=$('<select>');
            this.__DOM=this._createOption(select);
            select.attr('name',this.getName());
            if(this.__VALUE)this.syncDom();
            return this;
        }
    }
    SelectCreator.prototype={
        setSelectOpt:function(selectOpt){
            this.__SELECT_OPT=selectOpt;
            return this;
        },
    }
    
    function TextCreator() {
        Creator.call(this,'text');
        this.create=function () {
            this.__DOM=$('<input>');
            this.__DOM.attr('name',this.getName());
            if(this.__VALUE)this.syncDom();
            return this;
        }
    }

    function CheckboxAndRadioCreator($type) {
        Creator.call(this,$type);
        this.__LABEL;
        this.__INPUT;
        this.__createInput=function(){
            return $('<input type="'+this.getType()+'">');
        }
        this.create=function () {
            this.__INPUT=this.__createInput();
            this.__INPUT.attr('name',this.getName());
            if(this.__VALUE)this.syncDom();
            if(this.__LABEL){
                this.__DOM=$('<label>');
                this.__DOM.text(this.__LABEL);
                this.__INPUT.prependTo(this.__DOM);
            }else{
                this.__DOM=this.getInput();
            }
            return this;
        }
        this.syncDom=function(){
            this.__INPUT.val(this.__VALUE);
            return this;
        }

        this.syncValue=function () {
            this.__VALUE=this.__INPUT.val();
            return this;
        }
        this.setLabel=function (label) {
            this.__LABEL=label;
            return this;
        }
        this.getInput=function () {
            return this.__INPUT;
        },
        this.checked=function (checked) {
            checked=checked===true?true:false;
            this.__INPUT.attr('checked',checked);
            return this;
        }
        this.isChecked=function () {
            return this.__INPUT.is(':checked');
        }
    }
    function CheckBoxCreator() {
        CheckboxAndRadioCreator.call(this,'checkbox');
    }
    function RadioCreator() {
        CheckboxAndRadioCreator.call(this,'radio');
    }
    function InputFactory(){
    }
    InputFactory.prototype={
        getSelectCreator:function(){
            return new SelectCreator();
        },
        getTextCreator:function () {
            return new TextCreator();
        },
        getCheckBoxCreator:function () {
            return new CheckBoxCreator();
        },
        getRadioCreator:function () {
            return new RadioCreator();
        },
        getRadioGroup:function (name) {
            return new RadioGroup(name);
        },
        getCheckBoxGroup:function (name) {
            return new CheckBoxGroup(name);
        }
    }
    function CheckBoxAndRadioGroup(name){
        this.__ITEMS=[];
        this.__NAME=name;
        this.add=function (item) {
            var index=this.__ITEMS.push(item)-1;
            return index;
        },
        this.get=function (index) {
            return this.__ITEMS[index];
        }
        this.getName=function () {
            return this.__NAME;
        }
        this.appendTo=function (parent) {
            for(var key in this.__ITEMS){
                this.__ITEMS[key].appendTo(parent);
            }
            return this;
        }
    }
    function CheckBoxGroup(name) {
        CheckBoxAndRadioGroup.call(this,name);
        this.getValue=function () {
            var res=[];
            for(var key in this.__ITEMS){
                if(this.__ITEMS[key].isChecked())res.push(this.__ITEMS[key].getValue());
            }
            return res;
        }
        this.setValue=function (value) {
            function isIn(item,val) {
                if(val instanceof Array){
                    return val.indexOf(item)==-1?false:true;
                }else{
                    return item==val;
                }
            }
            for(var key in this.__ITEMS){
                if(isIn(this.__ITEMS[key].getValue(),value)){
                    this.__ITEMS[key].checked(true);
                }else{
                    this.__ITEMS[key].checked(false);
                }
            }
            return this;
        }
    }
    function RadioGroup(name) {
        CheckBoxAndRadioGroup.call(this,name);
        this.getValue=function () {
            for(var key in this.__ITEMS){
                if(this.__ITEMS[key].isChecked())return this.__ITEMS[key].getValue();
            }
            return null;
        }
        this.setValue=function (value) {
            for(var key in this.__ITEMS){
                if(this.__ITEMS[key].getValue()==value){
                    this.__ITEMS[key].checked(true);
                }else{
                    this.__ITEMS[key].checked(false);
                }
            }
            return this;
        }
    }
    _._self={
        'creatorFactory':new InputFactory()
    };
})(jQuery,window);