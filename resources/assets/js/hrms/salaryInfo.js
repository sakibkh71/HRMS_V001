import Vue from 'vue';
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

new Vue({
    el: "#salaryInfoDiv",
    data: {
        message: 'Hello Vue!',
        salaryInfo:[],
        info_name: '',
        info_status: 0,
        info_amount: '',
        info_type: 'Allowance',
        errorsHtml: '',
        hdn_id: '',
        edit_info_name: null,
        edit_info_status: 0,
        edit_info_amount: '',
        edit_info_type: '',
        edit_errorsHtml: '',
        salaryInfoIndex: '',
        testVal: [],
    },
    mounted(){
        axios.get('/salaryInfo/getAllInfo').then(response => this.salaryInfo = response.data);
    },
    methods: {
        saveSalaryInfo: function(event){

            axios.post('/salaryInfo/add', {
                info_name: this.info_name,
                info_status: this.info_status,
                info_amount: this.info_amount,
                info_type: this.info_type,
            })
            .then((response) => { 

                this.salaryInfo.push(response.data.data);
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
        editSalaryInfo: function(id, index){
            
            this.salaryInfoIndex = index;

            axios.get("/salaryInfo/edit/"+id,{
            
            })
            .then((response) => {

                this.hdn_id = response.data.id;
                this.edit_info_name = response.data.salary_info_name;
                this.edit_info_amount = response.data.salary_info_amount;
                this.edit_info_status = response.data.salary_info_amount_status;
                this.edit_info_type = response.data.salary_info_type;
            })
            .catch(function (error) {
                swal('Error:','Edit function not working','error');
                document.getElementById("modal-edit-close-btn").click();
            });
        },
        updateSalaryInfo: function(){
            
            axios.post('/salaryInfo/edit',{
                hdn_id: this.hdn_id,
                edit_info_name: this.edit_info_name,
                edit_info_status: this.edit_info_status,
                edit_info_amount: this.edit_info_amount,
                edit_info_type: this.edit_info_type,
            })
            .then(response => { 
                
                document.getElementById("modal-edit-close-btn").click();
                
                this.salaryInfo[this.salaryInfoIndex].salary_info_name = this.edit_info_name;
                this.salaryInfo[this.salaryInfoIndex].salary_info_amount = this.edit_info_amount;
                this.salaryInfo[this.salaryInfoIndex].salary_info_amount_status = this.edit_info_status;
                this.salaryInfo[this.salaryInfoIndex].salary_info_type = this.edit_info_type;

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
        deleteSalaryInfo: function(id, index){

            var delSalaryInfo = this.salaryInfo;

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

                axios.get("/salaryInfo/delete/"+id+"/"+index,{
            
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
                    
                    //console.log("--ok--"+response);
                    delSalaryInfo.splice(response.data.indexId, 1);
                })
                .catch(function (error) {
                    //console.log("--error--"+error);
                    swal('Error:','Delete function not working','error');
                });
            });
        }
    }
});