
Vue.component('select2', {
   props: ['value'],
   template: '<select><slot></slot></select>',
   mounted: function() {
     var vm = this
     $(this.$el)
       .val(this.value).select2()
       .on('change', function() {
         vm.$emit('input', this.value)
       })
   },
   watch: {
     value: function(value) {
       $(this.$el).select2('val', value)
     }
   },
   destroyed: function() {
     $(this.$el).off().select2('destroy')
   }
 });

Vue.component('select2', {
   props: ['value'],
   template: '<select><slot></slot></select>',
   mounted: function() {
     var vm = this
     $(this.$el)
       .val(this.value).select2()
       .on('change', function() {
         vm.$emit('input', this.value)
       })
   },
   watch: {
     value: function(value) {
       $(this.$el).select2('val', value)
     }
   },
   destroyed: function() {
     $(this.$el).off().select2('destroy')
   }
 });

$(document).ready(function(){

var work = new Vue({
    el: '#journalId',
    data:{
      index:'',
      department_id:0,
      unit_id:0,
      branch_id:0,
      units:[],
      users:[],
      salary_type:'month',
      final_journal:[],
      errors:[],
      pdf_branch_id:0,
      pdf_department_id:0,
      pdf_unit_id:0,
      pdf_user_id:0,
      pdf_salary_month:0,
      emp_name_one: '',
      emp_name_two: '',
      emp_name_three: '',
      emp_name_four: '',
      emp_name_five: '',
      emp_name_six: '',
      emp_name_seven: '',
      emp_name_eight: '',
      emp_name_nine: '',
      emp_desig_one: '',
      emp_desig_two: '',
      emp_desig_three: '',
      emp_desig_four: '',
      emp_desig_five: '',
      emp_desig_six: '',
      emp_desig_seven: '',
      emp_desig_eight: '',
      emp_desig_nine: '',
    },


    watch:{
      department_id: function(id){
        if(id !=0){
            this.getUnitByDepartmentId(id);
        }else{
          this.units = [];
        }
        this.getEmployee();
      },

      unit_id: function(){
        this.getEmployee();
      },

      branch_id: function(){
        this.getEmployee();
      },
    },

    mounted: function (){

      axios.get('/journal/getSignEmp',{
          
      })
      .then((response) => {
          
          this.emp_names = response.data;

          this.emp_name_one = this.emp_names[0].name;
          this.emp_name_two = this.emp_names[1].name;
          this.emp_name_three = this.emp_names[2].name;
          this.emp_name_four = this.emp_names[3].name;
          this.emp_name_five = this.emp_names[4].name;
          this.emp_name_six = this.emp_names[5].name;
          this.emp_name_seven = this.emp_names[6].name;
          this.emp_name_eight = this.emp_names[7].name;
          this.emp_name_nine = this.emp_names[8].name;
          this.emp_desig_one = this.emp_names[0].desig;
          this.emp_desig_two = this.emp_names[1].desig;
          this.emp_desig_three = this.emp_names[2].desig;
          this.emp_desig_four = this.emp_names[3].desig;
          this.emp_desig_five = this.emp_names[4].desig;
          this.emp_desig_six = this.emp_names[5].desig;
          this.emp_desig_seven = this.emp_names[6].desig;
          this.emp_desig_eight = this.emp_names[7].desig;
          this.emp_desig_nine = this.emp_names[8].desig;
          
      })
      .catch(function (error) {
         
          swal('Error:','Delete function not working','error');
      });  
    },

    methods:{

      saveEmpSign(data){

        var formData = $('#'+data).serialize();

        axios.post('/journal/saveEmpSign', formData)
        .then((response) => { 

            $('#create-form-errors').html('');
            
            if(response.data.title == 'error'){
              // swal({
              //   title: response.data.title+"!",
              //   text: response.data.message,
              //   type: response.data.title,
              //   showCancelButton: false,
              //   confirmButtonColor: "#DD6B55",
              //   confirmButtonText: "Done",
              //   closeOnConfirm: true
              // });
            }
            else{
              swal({
                  title: response.data.title+"!",
                  text: response.data.message,
                  type: response.data.title,
                  showCancelButton: false,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Done",
                  closeOnConfirm: false
              },
              function(){
                  location.href=location.href;
              });
            }
        })
        .catch((error) => {
        
            // if(error.response.status != 200){ //error 422
            
            //     var errors = error.response.data;

            //     var errorsHtml = '<div class="alert alert-danger"><ul>';
            //     $.each( errors , function( key, value ) {
            //         errorsHtml += '<li>' + value[0] + '</li>';
            //     });
            //     errorsHtml += '</ul></di>';
            //     $( '#create-form-errors' ).html( errorsHtml );
            // }
        });
      },

      dataTableCall(id){
        $(id).dataTable({
          "destroy": true,
          "paging":   true,
          "searching": true,
          "info": true,
          "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',
        }); 
      },
      

      dataTableDestroy(id){
        $(id).dataTable().fnDestroy(); 
      },


      dataTableGenerate(id='#datatableCall'){
        vueThis = this;
        if(this.dataTable){
          setTimeout(function(){vueThis.dataTableCall(id);}, 5);
          this.dataTable = false;
        }else{
          this.dataTableDestroy(id);
          setTimeout(function(){vueThis.dataTableCall(id);}, 5);
        }
      },


      showMessage(data){
        new PNotify({
            title: data.title,
            text: data.message,
            shadow: true,
            addclass: 'stack_top_right',
            type: data.status,
            width: '290px',
            delay: 2000,
            icon: false,
        });
      },


      loadinShow(idClass){
        $(idClass).LoadingOverlay("show",{color:"rgba(0, 0, 0, 0)"});
      },


      loadinHide(idClass){
        $(idClass).LoadingOverlay("hide",{color:"rgba(0, 0, 0, 0)"});
      },


      myMonthPicker(){
          $('.myMonthPicker').datetimepicker({
              format: 'YYYY-MM',
              minViewMode: 'months',
              viewMode: 'months',
              pickTime: false,
          });
      },


      myDatePicker(){
        $('.myDatePicker').datetimepicker({
              format: 'YYYY-MM-DD',
              // maxDate:new Date(),
              pickTime: false
        });
      },

      getUnitByDepartmentId(id){
          axios.get('/get-unit-by-department-id/'+id).then(response => {
              this.units = response.data;
              // console.log(this.designations);
          });
      },


      getEmployee(){
        axios.get('/payroll/index/'+this.branch_id+'/'+this.department_id+'/'+this.unit_id).then(response => {
            this.users = response.data;
            // console.log(this.supervisors);
        });
      },

      generateJournal(e){

        this.loadinShow('#journalId');
        let formData = new FormData(e.target);

        axios.post('/journal/index', formData).then(response => {

          this.pdf_branch_id = response.data[0].pdf_branch_id;
          this.pdf_department_id = response.data[0].pdf_department_id;
          this.pdf_unit_id = response.data[0].pdf_unit_id;
          this.pdf_salary_month = response.data[0].pdf_salary_month;

          console.log(response.data[0]);
          
          this.final_journal = response.data;

          this.errors = [];
          this.loadinHide('#journalId');

        }).catch(error => {
          
          this.loadinHide('#journalId');
          if(error.response.status == 500 || error.response.data.status == 'danger'){
              var error = error.response.data;
              this.showMessage(error);
          }else if(error.response.status == 422){
              this.errors = error.response.data;
          }
        });
      }

    }

  });


  });



