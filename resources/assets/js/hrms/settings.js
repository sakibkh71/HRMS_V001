import Vue from 'vue';
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

new Vue({
	el: "#mainDiv",
    data: {
        field_name: '',
        field_value: '',
        allValues: [],
        edit_field_name: '',
        edit_field_value: '',
        indexValue: null,
        hdn_id: '',
    },
    mounted(){
    	// axios.get('getSettings').then(response => this.allValues = response.data);
        axios.get('/settings/getSettings').then(response => this.allValues = response.data);
    },
    methods:{
    	saveSettings: function(formId){

    		var formData = $('#'+formId).serialize();

            axios.post('/settings/add', formData)
            .then((response) => { 

                $('#create-form-errors').html('');
                this.allValues.push(response.data.data);
                document.getElementById("modal-close-btn").click();

                new PNotify({
                    title: response.data.title+' Message',
                    text: response.data.message,
                    shadow: true,
                    addclass: 'stack_top_right',
                    type: response.data.title,
                    width: '290px',
                    delay: 1500
                });
            })
            .catch((error) => {
                //console.log("Errorrr: "+error);
                if(error.response.status != 200){ //error 422
                
                    var errors = error.response.data;

                    var errorsHtml = '<div class="alert alert-danger"><ul>';
                    $.each( errors , function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></di>';
                    $( '#create-form-errors' ).html( errorsHtml );
                }
            });
    
        },
        editSettings: function(id, index){

        	this.indexValue = index;
            this.hdn_id = id;
            this.edit_field_name = this.allValues[index].field_name;
            this.edit_field_value = this.allValues[index].field_value;
        },
        updateSettings: function(updateFormId){
            
        	var formData = $('#'+updateFormId).serialize();

            axios.post('/settings/edit', formData)
            .then(response => { 
               
               	$('#edit-form-errors').html('');
                document.getElementById("modal-edit-close-btn").click();

                this.allValues[this.indexValue].field_value = response.data.field_value;

                new PNotify({
                    title: response.data.title+' Message',
                    text: response.data.message,
                    shadow: true,
                    addclass: 'stack_top_right',
                    type: response.data.title,
                    width: '290px',
                    delay: 1500
                });
            })
            .catch( (error) => {
                var errors = error.response.data;

                var errorsHtml = '<div class="alert alert-danger"><ul>';
                $.each( errors , function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></di>';
                $( '#edit-form-errors' ).html( errorsHtml );
            });
        },
     //    deleteUnit: function(id, index){

     //        var delUnits = this.units;

     //        swal({
     //            title: "Are you sure?",
     //            text: "You will not be able to recover this information!",
     //            type: "warning",
     //            showCancelButton: true,
     //            confirmButtonColor: "#DD6B55",
     //            confirmButtonText: "Yes, delete it!",
     //            closeOnConfirm: false
     //        },
     //        function(){
                
     //            swal("Deleted!", "Your imaginary file has been deleted.", "success");
     //            axios.get("/unit/delete/"+id+"/"+index,{
            
     //            })
     //            .then((response) => {

     //            	//console.log("--response--"+response);
     //            	//console.log(delUnits+"---fff--"+response.data.indexId);

     //                // new PNotify({
     //                //     title: response.data.title+' Message',
     //                //     text: response.data.message,
     //                //     shadow: true,
     //                //     addclass: 'stack_top_right',
     //                //     type: response.data.title,
     //                //     width: '290px',
     //                //     delay: 1500
     //                // });
                    
     //                delUnits.splice(response.data.indexId, 1);
     //            })
     //            .catch(function (error) {
                   
     //                swal('Error:','Delete function not working','error');
     //            });
     //        });


     //    }
    }
});