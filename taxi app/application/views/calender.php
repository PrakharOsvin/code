<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Persian DatePicker</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="../public/persDatePick/css/persian-datepicker.css" rel="stylesheet" type="text/css"/>

    <!--<link href="../dist/css/theme/persian-datepicker-blue.css" rel="stylesheet" type="text/css"/>-->
    <link href="../dist/css/theme/persian-datepicker-dark.css" rel="stylesheet" type="text/css"/>

    <link href="../dist/css/theme/persian-datepicker-redblack.css" rel="stylesheet" type="text/css"/>

    <link href="../dist/css/theme/persian-datepicker-cheerup.css" rel="stylesheet" type="text/css"/>


    <script type="text/javascript" src="../lib/jquery.js"></script>
    <script type="text/javascript" src="../lib/persian-date.js"></script>

    <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../lib/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="../public/persDatePick/js/mousewheel.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/plugin.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/constant.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/config.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/template.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/base-class.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/compat-class.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/helper.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/monthgrid.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/monthgrid-view.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/datepicker-view.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/datepicker.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/navigator.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/daypicker.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/monthpicker.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/yearpicker.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/toolbox.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/timepicker.js"></script>
    <script type="text/javascript" src="../public/persDatePick/js/state.js"></script>
    <style>
        .center-content {
            display: block;
            width: 700px;
            margin: 0px auto 0px auto;
            min-height: 300px;
            overflow: hidden;
        }

        input {
            width: 300px;
            height: 24px;
        }
    </style>
</head>
<body>
<script type="text/javascript">
    /*
     Default Functionality
     */
    $(document).ready(function () {
        /*
         Default
         */
        window.pd = $("#inlineDatepicker").persianDatepicker({
            altField: '#inlineDatepickerAlt',
            altFormat: 'unix',
            altFieldFormatter: function (unixDate) {
                var self = this;
                var thisAltFormat = self.altFormat.toLowerCase();
                return new persianDate(unixDate).format();
            },
            timePicker: {
                enabled: true,
                showSeconds: true,
                showMeridian: false,
                scrollEnabled: true
            }

        }).data('datepicker');

        //$("#inlineDatepicker").pDatepicker('destroy');

        console.log(window.pd.setDate);


        //pd.setDate([1333,12,28,11,20,30]);

        /**
         * Default
         * */
        $('#default').persianDatepicker({
            altField: '#defaultAlt'

        });


        /*
         observer
         */
        $("#observer").persianDatepicker({
            altField: '#observerAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            observer: true,
            format: 'YYYY/MM/DD'

        });

        /*
         timepicker
         */
        $("#timepicker").persianDatepicker({
            altField: '#timepickerAltField',
            altFormat: "YYYY MM DD HH:mm:ss",
            format: "HH:mm:ss a",
            onlyTimePicker: true

        });
        /*
         month
         */
        $("#monthpicker").persianDatepicker({
            format: " MMMM YYYY",
            altField: '#monthpickerAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            yearPicker: {
                enabled: false
            },
            monthPicker: {
                enabled: true
            },
            dayPicker: {
                enabled: false
            }
        });

        /*
         year
         */
        $("#yearpicker").persianDatepicker({
            format: "YYYY",
            altField: '#yearpickerAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            dayPicker: {
                enabled: false
            },
            monthPicker: {
                enabled: false
            },
            yearPicker: {
                enabled: true
            }
        });
        /*
         year and month
         */
        $("#yearAndMonthpicker").persianDatepicker({
            format: "YYYY MM",
            altFormat: "YYYY MM DD HH:mm:ss",
            altField: '#yearAndMonthpickerAlt',
            dayPicker: {
                enabled: false
            },
            monthPicker: {
                enabled: true
            },
            yearPicker: {
                enabled: true
            }
        });
        /**
         inline with minDate and maxDate
         */
        $("#inlineDatepickerWithMinMax").persianDatepicker({
            altField: '#inlineDatepickerWithMinMaxAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            minDate: 1416983467029,
            maxDate: 1419983467029
        });
        /**
         Custom Disable Date
         */
        $("#customDisabled").persianDatepicker({
            timePicker: {
                enabled: true
            },
            altField: '#customDisabledAlt',
            checkDate: function (unix) {
                var output = true;
                var d = new persianDate(unix);
                if (d.date() == 20 | d.date() == 21 | d.date() == 22) {
                    output = false;
                }
                return output;
            },
            checkMonth: function (month) {
                var output = true;
                if (month == 1) {
                    output = false;
                }
                return output;

            }, checkYear: function (year) {
                var output = true;
                if (year == 1396) {
                    output = false;
                }
                return output;
            }

        });

        /**
         persianDate
         */
        $("#persianDigit").persianDatepicker({
            altField: '#persianDigitAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            persianDigit: false
        });
    });
</script>
<div class="col-xs-12">
    <div class="col-xs-9">
        <h1>Persian Date Picker Demo</h1>

        <p>
            <span class="label label-success">Version 0.4.2</span>
        </p>
    </div>
    <div class="col-xs-3">
        <!--<label>Theme</label>-->
        <!--<select class="form-control theme-roller">-->
        <!--<option value="default">Default</option>-->
        <!--<option value="dark">Dark</option>-->
        <!--<option value="blue">Blue</option>-->
        <!--</select>-->
    </div>
</div>
<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Inline Datepicker</h4>
        </div>
        <div class="panel-body">
            <div id="inlineDatepicker" data-date="2016/12/13 12:20" class="col-xs-6"></div>
            <div class="col-xs-6">
                <input id="inlineDatepickerAlt" type="text" class="form-control"/>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Default Functionlaity</h4>
        </div>
        <div class="panel-body">

            <div class="form-group col-xs-6">
                <label>Select Date</label>
                <input id="default" type="text" class="form-control"/>
            </div>
            <div class="form-group col-xs-6">
                <input id="defaultAlt" type="text" class="form-control"/>
            </div>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Observer Functionlaity</h4>
            <h6>sync with pasted data</h6>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-6">
                <label>Select Date</label>
                <input id="observer" type="text" class="form-control"/>
            </div>
            <div class="form-group col-xs-6">
                <input id="observerAlt" type="text" class="form-control"/>
            </div>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Time Picker</h4>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-6">
                <label>Select Time</label>
                <input id="timepicker" type="text" class="form-control"/>
            </div>
            <div class="form-group col-xs-6">
                <input type="text" id="timepickerAltField" class="form-control"/>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Month Picker</h4>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-6">
                <h4>Select Month</h4>
                <input id="monthpicker" type="text" class="form-control"/>
            </div>
            <div class="form-group col-xs-6">
                <input id="monthpickerAlt" type="text" class="form-control"/>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Year Picker</h4>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-6">
                <label>Select Year</label>
                <input id="yearpicker" type="text" class="form-control"/>
            </div>
            <div class="form-group col-xs-6">
                <input id="yearpickerAlt" type="text" class="form-control"/>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Year And Month Picker</h4>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-6">
                <label>Select Year And Month</label>
                <input id="yearAndMonthpicker" type="text" class=" form-control"/>
            </div>
            <div class="form-group col-xs-6">
                <input id="yearAndMonthpickerAlt" type="text" class=" form-control"/>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>With Min and Max</h4>
        </div>
        <div class="panel-body">
            <div id="inlineDatepickerWithMinMax" class="col-xs-6"></div>
            <div class="col-xs-6">
                <input id="inlineDatepickerWithMinMaxAlt" type="text" class=" form-control"/>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Custom Date Disabled</h4>
        </div>
        <div class="panel-body">
            <div id="customDisabled" class="col-xs-6"></div>
            <div class="col-xs-6">
                <input id="customDisabledAlt" type="text" class=" form-control"/>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>English Digit</h4>
        </div>
        <div class="panel-body">

            <div id="persianDigit" class="col-xs-6"></div>
            <div class="col-xs-6">
                <input id="persianDigitAlt" type="text" class=" form-control"/>
            </div>
        </div>
    </div>
</div>
</body>
</html>