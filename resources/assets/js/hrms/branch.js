import Vue from 'vue';
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

new Vue({
	el: "#mainDiv",
	data:{
		branches: [],
		branch_name: '',
		branch_email: '',
		branch_mobile: '',
		branch_phone: '',
		branch_location: '',
        branch_effective_date: '',
        branch_description: '',
		branch_status: '1',
        edit_branch_name: '',
        edit_branch_email: '',
        edit_branch_mobile: '',
        edit_branch_phone: '',
        edit_branch_location: '',
        edit_branch_description: '',
        edit_branch_effective_date: '',
        edit_branch_status: '',
        indexId: '',
        hdn_id: null,
	},
	mounted(){
    	
    	axios.get('/branch/getBranch').then(response => this.branches = response.data);
    },
    methods:{
    	saveBranch(formId){
    		var formData = $('#'+formId).serialize();

            axios.post('/branch/add', formData)
            .then((response) => { 

                $('#create-form-errors').html('');
                this.branches.push(response.data.data);
                document.getElementById("modal-close-btn").click();

                //empty text field after save data
                this.branch_name = '',
                this.branch_email = '',
                this.branch_mobile = '',
                this.branch_phone = '',
                this.branch_location = '',              

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
        editData(id, index){

            this.indexId = index;
            this.hdn_id = id;
            this.edit_branch_name = this.branches[index].branch_name;
            this.edit_branch_email = this.branches[index].branch_email;
            this.edit_branch_mobile = this.branches[index].branch_mobile;
            this.edit_branch_phone = this.branches[index].branch_phone;
            this.edit_branch_location = this.branches[index].branch_location;
            this.edit_branch_effective_date = this.branches[index].branch_effective_date;
            this.edit_branch_description = this.branches[index].branch_description;
            this.edit_branch_status = this.branches[index].branch_status;
        },
        updateData: function(updateFormId){
            
            var formData = $('#'+updateFormId).serialize();

            axios.post('/branch/edit', formData)
            .then(response => { 
               
                $('#edit-form-errors').html('');
                document.getElementById("modal-edit-close-btn").click();
                
                this.branches[this.indexId].branch_name = this.edit_branch_name;
                this.branches[this.indexId].branch_email = this.edit_branch_email;
                this.branches[this.indexId].branch_mobile = this.edit_branch_mobile;
                this.branches[this.indexId].branch_phone = this.edit_branch_phone;
                this.branches[this.indexId].branch_location = this.edit_branch_location;
                this.branches[this.indexId].branch_effective_date = this.edit_branch_effective_date;
                this.branches[this.indexId].branch_description = this.edit_branch_description;
                this.branches[this.indexId].branch_status = this.edit_branch_status;

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
        deleteData: function(id, index){

            var delBranch = this.branches;

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this information!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                
                swal("Deleted!", "Your imaginary file has been deleted.", "success");

                axios.get("/branch/delete/"+id+"/"+index,{
            
                })
                .then((response) => {

                    new PNotify({
                        title: response.data.title+' Message',
                        text: response.data.message,
                        shadow: true,
                        addclass: 'stack_top_right',
                        type: response.data.title,
                        width: '290px',
                        delay: 1500
                    });
                    
                    delBranch.splice(response.data.indexId, 1);
                })
                .catch(function (error) {
                    
                    swal('Error:','Delete function not working','error');
                });
            });
        }
    }
});