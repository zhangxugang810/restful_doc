var lineId = 1;
$.auxiliary = {
    status:0,
    moveStatus:[],
    setStatus:function(status){
        this.status = status;
    },
    
    setMoveStatus:function(id, status){
        this.moveStatus[id] = status;
    },
    
    setEvt:function(obj, lineId){
        var o = this;
        obj.mousedown(function(){o.setMoveStatus(lineId, 1);});
        obj.mouseup(function(){o.setMoveStatus(lineId, 0);});
    },
    
    setMoveEvt:function(type, lineId){
        var o = this;
        var obj = $('#line_'+lineId);
        if(type == 'Transverse'){
            $('body').bind('mousemove', function(e){
                o.moveTransverse(e,obj);
            });
        }else if(type == 'Longitudinal'){
            $('body').bind('mousemove', function(e){
                o.moveLongitudinal(e,obj);
            });
        }
    },
    
    /**
     * 设置横向辅助线
     * @returns {undefined}
     */
    setTransverse:function(e){
        if(this.status == 1){
            var html = '<div id="line_'+lineId+'" class="transverseLine"></div>';
            $('body').append(html);
            var top = e.clientY;
            var obj = $('#line_'+lineId);
            this.setMoveStatus(lineId, 1);
            obj.css('top',top);
            this.setEvt(obj, lineId);
            this.setMoveEvt('Transverse', lineId);
            lineId++;
        }
    },
    
    
    
    /**
     * 设置纵向辅助线
     * @returns {undefined}
     */
    setLongitudinal:function(e){
        if(this.status == 1){
            var html = '<div id="line_'+lineId+'" class="longitudinalLine"></div>';
            $('body').append(html);
            var left = e.clientX;
            var obj = $('#line_'+lineId);
            this.setMoveStatus(lineId, 1);
            obj.css('left',left);
            this.setEvt(obj, lineId);
            this.setMoveEvt('Longitudinal', lineId);
            lineId++;
        }
    },
    
    moveTransverse:function(e, obj){
        var id = parseInt(obj.attr('id').replace('line_',''));
        if(this.moveStatus[id] == 1){
            var top = e.clientY;
            if(top > 56){
                obj.css('top',top);
            }else{
                this.delLine(obj);
//                this.setStatus(1);
            }
        }
    },
    
    moveLongitudinal:function(e, obj){
        var id = parseInt(obj.attr('id').replace('line_',''));
        if(this.moveStatus[id] == 1){
            var left = e.clientX;
            if(left > 17){
                obj.css('left',left);
            }else{
                this.delLine(obj);
//                this.setStatus(1);
            }
        }
    },
    
    delLine:function(obj){
        obj.remove();
    }
};