
function checkPosition(e) {
    if ((e.target.nodeName === 'TD') && (e.target.childElementCount === 0)) { 
        var box = e.target; 
        $(box).html(`<img src=${box.dataset.imgplayerpath}>`); 
        $.ajax({
            type: 'POST', 
            url: 'index.php', 
            dataType: "json", 
            data: {
                x: box.dataset.x,
                y: box.dataset.y
            },
            success: function (result) { 
                console.log(result);
                if (result.x !== undefined) { 
                    $(`#${result.x}${result.y}`).html(`<img src=${box.dataset.imgcomputerpath}>`); 
                }
                    if (result.gameRes !== undefined) { 
                    switch (result.gameRes) {
                        case 0: 
                            $("#message").text("Draw!!");
                            break;
                        case 1: 
                            $("#message").text("You Win!!");
                            break;
                        case - 1: 
                            $("#message").text("You lost!!");
                            break;
                    }
                    $('table').unbind('click'); 
                }
            },
            error: function (xhr, status, error) { 
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Error - ' + errorMessage);
            }
        });
    }
}
;
$(document).ready(function () {
    $('table').click(checkPosition); 
});
