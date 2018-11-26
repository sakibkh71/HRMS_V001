
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
    el: '#bankAdvice',
    data:{
      index:'',
      department_id:0,
      unit_id:0,
      branch_id:0,
      units:[],
      users:[],
      salary_type:'month',
      payRoll:[],
      adviceReport:[],
      errors:[],
      advice_type: 'bank',
      pdf_branch_id:0,
      pdf_department_id:0,
      pdf_unit_id:0,
      pdf_user_id:0,
      pdf_salary_month:0,
      cover_letter: null,
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

      axios.get('/payrollBankAdvice/getCoverLetter',{
          
      })
      .then((response) => {
          
          this.cover_letter = response.data;
          
      })
      .catch(function (error) {
         
          swal('Error:','Delete function not working','error');
      });  
    },

    methods:{

      saveCoverLetter(data){

        for ( instance in CKEDITOR.instances ) {
            CKEDITOR.instances[instance].updateElement();
        }

        var formData = $('#'+data).serialize();

        axios.post('/payrollBankAdvice/saveCoverLetter', formData)
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

      // getFullName(data){
      //   if(data){
      //     let fullname = '<a href="/employee/view/'+data.employee_no+'" target="_blank">';
      //     fullname += (data.first_name)?data.first_name+' ':'';
      //     fullname += (data.last_name)?data.last_name:'';
      //     fullname +='</a>';
      //     return fullname;
      //   }
      // },

      generateAdviceSheet(e){

        this.loadinShow('#bankAdvice');
        let formData = new FormData(e.target);

        axios.post('/payrollBankAdvice/index', formData).then(response => {
        
          if(response.data.length > 0){
            this.pdf_branch_id = response.data[0].pdf_branch_id;
            this.pdf_department_id = response.data[0].pdf_department_id;
            this.pdf_unit_id = response.data[0].pdf_unit_id;
            this.pdf_user_id = response.data[0].pdf_user_id;
            this.pdf_salary_month = response.data[0].pdf_salary_month;
          }
        
          this.adviceReport = response.data;
          this.errors = [];
          this.loadinHide('#bankAdvice');

        }).catch(error => {
          
          this.loadinHide('#bankAdvice');
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



