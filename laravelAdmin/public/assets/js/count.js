function countUp(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}

//countUp(495);

function countUp2(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count2'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1; 
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}

//countUp2(947);

function countUp3(count)
{
if(isNaN(count))
{count=0;}


//count=(Math.round(count));
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count3'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text("$"+speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text("$"+curr_count);
        } else {
            clearInterval(int);
$display.text("$"+ parseFloat(count).toFixed(2));

        }
    }, int_speed);
}

//countUp3(328);

function countUp4(count)
{

    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count4'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);



}

//countUp4(10328);

function countUp5(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count5'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}

function countUp6(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count6'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}
function countUp7(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count7'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}
function countUp8(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count8'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}
function countUp9(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count9'),
        run_count =999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}
function countUp10(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count10'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}
function countUp11(count)
{
    var div_by = 100,
        speed = Math.round(count / div_by),
        $display = $('.count11'),
        run_count = 999999,
        int_speed = 24;

    var int = setInterval(function() {
        if(run_count < div_by){
            $display.text(speed * run_count);
            run_count++;
        } else if(parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}