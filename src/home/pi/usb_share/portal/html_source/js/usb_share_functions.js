function updateElement(id,val) {
    var element_id = id;
    var new_content = val;
    document.getElementById(element_id).innerHTML = new_content;
}

function showElement(element_id) {
    $e = document.getElementById(element_id);
    $e.classList.remove("hidden");
    $e.classList.add("show");
}

function hideElement(element_id) {
    $e = document.getElementById(element_id);
    $e.classList.remove("show");
    $e.classList.add("hidden");
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}