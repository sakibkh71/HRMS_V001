
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
    el: '#reconciliationId',
    data:{
      index:'',
      department_id:0,
      unit_id:0,
      branch_id:0,
      units:[],
      users:[],
      salary_type:'month',
      reconciliationReport:[],
      errors:[],
      pdf_branch_id:0,
      pdf_department_id:0,
      pdf_unit_id:0,
      pdf_user_id:0,
      pdf_salary_month:0,
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

    methods:{

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

      generateReconciliation(e){

        this.loadinShow('#reconciliationId');
        let formData = new FormData(e.target);

        axios.post('/payrollReconciliation/index', formData).then(response => {

          this.pdf_branch_id = response.data.pdf_branch_id;
          this.pdf_department_id = response.data.pdf_department_id;
          this.pdf_unit_id = response.data.pdf_unit_id;
          this.pdf_salary_month = response.data.pdf_salary_month;
          
          this.reconciliationReport = response.data;

          this.errors = [];
          this.loadinHide('#reconciliationId');

        }).catch(error => {
          
          this.loadinHide('#reconciliationId');
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



