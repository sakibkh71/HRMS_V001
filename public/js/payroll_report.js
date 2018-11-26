
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
      allowance_row_diff:null,
      department_id:0,
      unit_id:0,
      branch_id:0,
      units:[],
      users:[],
      salary_type:'month',
      payRoll:[],
      payRolls:[],
      errors:[],
      pdf_branch_id:0,
      pdf_department_id:0,
      pdf_unit_id:0,
      pdf_user_id:0,
      pdf_salary_month:0,
      emp_names: [],
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

      axios.get('/payroll/getSignEmp',{
          
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

        axios.post('/payroll/saveEmpSign', formData)
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
          "pageLength": 100,
          "aLengthMenu": [[100, 150, 200, -1], [100, 150, 200, "All"]], 
          "info": true,
          "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',
        }); 
      },
      

      dataTableDestroy(id){
        $(id).dataTable().fnDestroy(); 
      },

      paySlipGrossEarningCalculation(basic,cash,allowance, overtime){

         return parseInt(basic)+parseInt(cash)+parseInt(allowance)+parseInt(overtime);
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
            midClick: true,
            showCloseBtn: false
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

        axios.post('/payroll/salaries', formData).then(response => {

          if(response.data.length > 0){
            this.pdf_branch_id = response.data[0].pdf_branch_id;
            this.pdf_department_id = response.data[0].pdf_department_id;
            this.pdf_unit_id = response.data[0].pdf_unit_id;
            this.pdf_user_id = response.data[0].pdf_user_id;
            this.pdf_salary_month = response.data[0].pdf_salary_month;
          }
          
          this.payRolls = response.data;
          this.errors = [];
          this.loadinHide('#payroll');
          this.dataTableGenerate();

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


      paySlip(index,month_format){
        this.payRoll = this.payRolls[index];
        this.allowance_row_diff = 2+this.payRoll.allowances.length - this.payRoll.deductions.length;
        console.log(this.allowance_row_diff);
        this.modal_open('#payslip_modal');
      },


      PrintElem(elem)
      {
        $('#payslip_button').hide();
        var mywindow = window.open('', 'printwindow');
        mywindow.document.write('<html><head><title>Pay Slip</title><link rel="stylesheet" type="text/css" href="/css/hrms.css" />');
        mywindow.document.write('</head><body>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');
        setTimeout(function () {
          mywindow.print();
          mywindow.close();
          $('#payslip_button').show();
        }, 500);
        return true;
      },

      convertNumberToWords(amount) {
        amount = parseInt(amount);
        var words = new Array();
        words[0] = '';
        words[1] = 'One';
        words[2] = 'Two';
        words[3] = 'Three';
        words[4] = 'Four';
        words[5] = 'Five';
        words[6] = 'Six';
        words[7] = 'Seven';
        words[8] = 'Eight';
        words[9] = 'Nine';
        words[10] = 'Ten';
        words[11] = 'Eleven';
        words[12] = 'Twelve';
        words[13] = 'Thirteen';
        words[14] = 'Fourteen';
        words[15] = 'Fifteen';
        words[16] = 'Sixteen';
        words[17] = 'Seventeen';
        words[18] = 'Eighteen';
        words[19] = 'Nineteen';
        words[20] = 'Twenty';
        words[30] = 'Thirty';
        words[40] = 'Forty';
        words[50] = 'Fifty';
        words[60] = 'Sixty';
        words[70] = 'Seventy';
        words[80] = 'Eighty';
        words[90] = 'Ninety';
        amount = amount.toString();
        var atemp = amount.split(".");
        var number = atemp[0].split(",").join("");
        var n_length = number.length;
        var words_string = "";
        if (n_length <= 9) {
            var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
            var received_n_array = new Array();
            for (var i = 0; i < n_length; i++) {
                received_n_array[i] = number.substr(i, 1);
            }
            for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                n_array[i] = received_n_array[j];
            }
            for (var i = 0, j = 1; i < 9; i++, j++) {
                if (i == 0 || i == 2 || i == 4 || i == 7) {
                    if (n_array[i] == 1) {
                        n_array[j] = 10 + parseInt(n_array[j]);
                        n_array[i] = 0;
                    }
                }
            }
            value = "";
            for (var i = 0; i < 9; i++) {
                if (i == 0 || i == 2 || i == 4 || i == 7) {
                    value = n_array[i] * 10;
                } else {
                    value = n_array[i];
                }
                if (value != 0) {
                    words_string += words[value] + " ";
                }
                if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Crores ";
                }
                if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Lakhs ";
                }
                if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Thousand ";
                }
                if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                    words_string += "Hundred and ";
                } else if (i == 6 && value != 0) {
                    words_string += "Hundred ";
                }
            }
            words_string = words_string.split("  ").join(" ");
        }
        return words_string;
      },


    }

  });


  });



