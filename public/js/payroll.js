
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
    el: '#payroll',
    data:{
      index:'',
      removeAdvance: '',
      activeAdvance: '',
      department_id:0,
      unit_id:0,
      branch_id:0,
      units:[],
      users:[],
      salary_type:'month',
      payRoll:[],
      payRolls:[],
      errors:[],
      payment_procedure_bank:null,
      payment_procedure_cash:null,
      payment_procedure_both:null,
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


      modal_open(form_id) {
        this.errors = [];

        $.magnificPopup.open({
            removalDelay: 300,
            items: {
                src: form_id
            },
            callbacks: {
                beforeOpen: function (e) {
                    var Animation = "mfp-zoomIn";
                    this.st.mainClass = Animation;
                }
            },
            midClick: true
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


      getFullName(data){
        if(data){
          let fullname = '<a href="/employee/view/'+data.employee_no+'" target="_blank">';
          fullname += (data.first_name)?data.first_name+' ':'';
          fullname += (data.last_name)?data.last_name:'';
          fullname +='</a>';
          return fullname;
        }
      },


      generateSalary(e){
        this.loadinShow('#payroll');
        let formData = new FormData(e.target);

        axios.post('/payroll/index', formData).then(response => {
          this.payRolls = response.data;
          this.errors = [];
          this.loadinHide('#payroll');

        }).catch(error => {
          
          this.loadinHide('#payroll');
          if(error.response.status == 500 || error.response.data.status == 'danger'){
              var error = error.response.data;
              this.showMessage(error);
          }else if(error.response.status == 422){
              this.errors = error.response.data;
          }
        });
      },

      editSalary(user_id, index, model_id){
        this.loadinShow(model_id);
        this.index = index;
        this.payRoll = this.payRolls[index];
        this.modal_open(model_id);
        this.loadinHide(model_id);
      },

      updateSalary(){
        this.payRoll.salary = parseFloat(this.payRoll.perday_salary_with_out_allowance * parseInt(this.payRoll.payment_days)).toFixed(2);
        this.payRoll.perhour_salary = (this.payRoll.perday_salary) / (this.payRoll.work_hour);
        this.payRoll.overtime_amount = (this.payRoll.perhour_salary) * (this.payRoll.overtime_hour);
        this.payRoll.total_allowance = parseFloat((this.payRoll.total_allowance_per_day * this.payRoll.payment_days) + this.payRoll.bonus_allowance).toFixed(2);
        this.payRoll.net_salary = (this.payRoll.salary) - (this.payRoll.total_deduction);
        this.payRoll.total_salary = (parseFloat(this.payRoll.salary) + parseFloat(this.payRoll.overtime_amount) + parseFloat(this.payRoll.total_allowance)) - parseFloat(this.payRoll.total_deduction);
        this.payRoll.total_salary = Math.round(this.payRoll.total_salary);
      },

      updatePaymentProcedure(){
        this.payRoll.payment_procedure = this.payRoll.payment_procedure;
      },

      processAll(){

        swal({
          title: "Are you sure?",
          text: "All employee salary will be processed! Please check again ...",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {

              this.loadinShow('#payroll');
              let formData = this.payRolls;
              // console.log(formData);
              axios.post('/payroll/addAll', formData).then(response => {

                console.log(response);
                
                this.errors = [];
                this.loadinHide('#payroll');
                this.showMessage(response.data);

              }).catch(error => {
                // console.log(error);
                this.loadinHide('#payroll');
                if(error.response.status == 500 || error.response.data.status == 'danger'){
                    var error = error.response.data;
                    this.showMessage(error);
                }else if(error.response.status == 422){
                    this.errors = error.response.data;
                }
              });

              swal("Salary process sucessfully!", {
              icon: "success",
            });
          } 
          else {
            swal("Salary not process!");
          }
        });
      },

      comfirmSalary(user_id, index){
        this.loadinShow('#payroll');
        let formData = this.payRolls[index];
        // console.log(formData);
        axios.post('/payroll/add', formData).then(response => {

          console.log(response);
          
          this.errors = [];
          this.loadinHide('#payroll');
          this.showMessage(response.data);

        }).catch(error => {
          // console.log(error);
          this.loadinHide('#payroll');
          if(error.response.status == 500 || error.response.data.status == 'danger'){
              var error = error.response.data;
              this.showMessage(error);
          }else if(error.response.status == 422){
              this.errors = error.response.data;
          }
        });
      },

      removeAdvanceFunction(indexSl, index, statt, activeOrRemove, amount){
        
        if(activeOrRemove == 'remove'){
          if(statt){
            this.payRolls[indexSl].deductions[index].name = 'Advance-remove';
            this.activeAdvance = false;

            // this.payRoll.total_deduction = amount;
            this.payRolls[indexSl].total_deduction = this.payRolls[indexSl].total_deduction - parseInt(amount);
            this.payRolls[indexSl].total_salary = this.payRolls[indexSl].total_salary + parseInt(amount);
            console.log(amount, this.payRolls[indexSl].total_deduction);
          }
        }

        if (activeOrRemove == 'active'){
          if(statt){
            this.payRolls[indexSl].deductions[index].name = 'Advance';
            this.removeAdvance = false;
            
            // this.payRoll.total_deduction = amount;
            this.payRolls[indexSl].total_deduction = this.payRolls[indexSl].total_deduction + parseInt(amount);
            this.payRolls[indexSl].total_salary = this.payRolls[indexSl].total_salary - parseInt(amount);
            console.log(this.payRolls[indexSl].total_deduction);
          }
        }
        
      }


    }

  });


  });



