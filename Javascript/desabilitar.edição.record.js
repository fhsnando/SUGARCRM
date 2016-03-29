/**
 * Created by Fernando Santos on 29/03/2016.
 */

({
    extendsFrom : 'RecordView',

    initialize: function(options) {
        this._super('initialize', [options]);
        that=this;
        //add custom message key
        var error="Não é permitido editar atendimentos encerrados";
        app.error.errorName2Keys['ERRO2'] = error;
        this.model.addValidationTask('stop_create_registry', _.bind(this._doValidateCheckType, this));
    },

    editClicked: function () {
        if(this.model.get('checkat_c') == true){
            this.cancelClicked();
            this.showMessage();
        }else{
            this.setButtonStates(this.STATE.EDIT);
            this.toggleEdit(true);
        }
    },

    toggleFields: function(fields, isEdit) {
        if(that.model.get('checkat_c') == true){
            this.showMessage();
        }else{
            this._super('toggleFields', [fields],[isEdit]);
        }
    },

    handleEdit: function (e, cell) {
        if(that.model.get('checkat_c') == true){
            this.showMessage();
        }else{
            this._super('handleEdit', [e],[cell]);
        }
        //var target, cellData, field;
        //if (e) {
        //    target = this.$(e.target);
        //    cell = target.parents('.record-cell');
        //}
        //cellData = cell.data();
        //field = this.getField(cellData.name);
        //this.inlineEditMode = true;
        //this.setButtonStates(this.STATE.EDIT);
        //this.toggleField(field);
        //if (cell.closest('.headerpane').length > 0) {
        //    this.toggleViewButtons(true);
        //    this.adjustHeaderpaneFields();
        //}
    },

    showMessage:function(){
        //mensagem de erro padrão chugar.
        app.alert.show('address-ok', {
            level: 'error',
            messages: 'Esse atendimento ja foi concluido e não pode ser editado',
            autoClose: true
        });
    },


    _doValidateCheckType: function(fields, errors, callback) {
        if (this.model.get('checkat_c') == true){
            errors['checkat_c'] = errors['checkat_c'] || {};
            errors['checkat_c'].ERRO2 = true;
            var error="Não é permitido editar atendimentos encerrados";
            app.alert.show('message-id', {
                level: 'error',
                messages: error,
                autoClose: false
                //onConfirm: function(){
                //    alert("Confirmed!");
                //}
            });
        }
        callback(null, fields, errors);
    }

})