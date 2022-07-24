function pausePrinting()
{
monoXControl(myDisplayer,'pause'); 
}
function resumePrinting()
{
monoXControl(myDisplayer,'resume'); 
}
function stopPrinting()
{
monoXControl(myDisplayer,'stop'); 
}

function myDisplayer(some) {
document.getElementById("status").innerHTML = some;
}

function monoXControl(myCallback, action) {
let req = new XMLHttpRequest();
req.open('GET', "/includes/mono_x_contol.php?action=" + action);
req.onload = function() {
    if (req.status == 200) {
    myCallback(this.responseText);
    sleep(6000);
    window.location.reload(true);
    } else {
    myCallback("Error: " + this.responseText);
    }
}
req.send();
}

function sleep(ms) {
return new Promise(resolve => setTimeout(resolve, ms));
}

<?php
        if($printer_status == "Stopped")
        {
        $fileList = explode(",",$printer_files);
        for($i = 1; $i < (count($fileList) - 1); $i++) {
            $fileNames = explode("/",$fileList[$i]);

            echo '
            function startPrinting' . $i . '() {
            action = \'print,' . $fileNames[1] . '\';
            monoXControl(myDisplayer,action);
            }
            document.getElementById("startPrinting' . $i . '").addEventListener("click", startPrinting' . $i . ');
            ';
        }
        }
        elseif($printer_status == "Printing")
        {
            ?>
            document.getElementById("pausePrinting").addEventListener("click", pausePrinting);
            document.getElementById("stopPrinting").addEventListener("click", stopPrinting);
            <?php
        }
        elseif($printer_status == "Paused")
        {
            ?>
            document.getElementById("resumePrinting").addEventListener("click", resumePrinting);
            <?php
        }
?>