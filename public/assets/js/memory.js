/////////////////////////////////////////////////////////////////////
////////////////////////// App functions
/////////////////////////////////////////////////////////////////////
function startTimer(duration, display, session_id) {
    let i = 0;
    let timer = setInterval(function () {
        let percent = Math.floor(parseFloat(++i / duration) * 100);
        if (percent <= 100) {
            display.progressBar.attr('aria-valuenow', percent).css('width', percent + '%').html(percent + '%');
        } else {
            stopTimer(timer, display);
            stoptGame(session_id).done(function (json) {
                alert("you lose");
                listGameUpdate();
            });
        }
    }, 1000);
    return timer;
}

function stopTimer(timer, display) {
    clearInterval(timer);
    display.progressBar.attr('aria-valuenow', 0).css('width', 0 + '%').html('');
    display.startBtn.prop("disabled", false);
}

function startGame() {
    return $.ajax({
        url: "/controller.php",
        data: {
            action: "start"
        },
        type: "GET",
        dataType: "json",
    }).fail(function (xhr, status, errorThrown) {
        alert("Sorry, there was a problem!");
        console.log("Error: " + errorThrown);
        console.log("Status: " + status);
        console.dir(xhr);
    }).always(function (xhr, status) {
        console.log("The request is complete!");
    });
}

function playGame(pline, pcolumn, session_id) {
    return $.ajax({
        url: "/controller.php",
        data: {
            action: "play",
            pline: pline,
            pcolumn: pcolumn,
            session_id: session_id
        },
        type: "GET",
        dataType: "json",
    }).fail(function (xhr, status, errorThrown) {
        alert("Sorry, there was a problem!");
        console.log("Error: " + errorThrown);
        console.log("Status: " + status);
        console.dir(xhr);
    }).always(function (xhr, status) {
        console.log("The request is complete!");
    });
}

function stoptGame(session_id) {
    return $.ajax({
        url: "/controller.php",
        data: {
            action: "end",
            session_id: session_id
        },
        type: "GET",
        dataType: "json",
    }).fail(function (xhr, status, errorThrown) {
        alert("Sorry, there was a problem!");
        console.log("Error: " + errorThrown);
        console.log("Status: " + status);
        console.dir(xhr);
    }).always(function (xhr, status) {
        console.log("The request is complete!");
    });
}

function listGame() {
    return $.ajax({
        url: "/controller.php",
        data: {
            action: "list"
        },
        type: "GET",
        dataType: "json",
    }).fail(function (xhr, status, errorThrown) {
        alert("Sorry, there was a problem!");
        console.log("Error: " + errorThrown);
        console.log("Status: " + status);
        console.dir(xhr);
    }).always(function (xhr, status) {
        console.log("The request is complete!");
    });
}

function listGameUpdate() {
    listGame().done(function (json) {
        let str = '';
        if (json.length > 0) {
            $.each(json, function () {
                str += '<tr>' +
                    '<td>' + this.id + '</td>' +
                    '<td>' + this.start_at + '</td>' +
                    '<td>' + this.end_at + '</td>' +
                    '<td>' + ((this.success == 1) ? 'yes' : 'no') + '</td>' +
                    '</tr>';
            });
            $('#list table tbody').html(str);
        }
    });
}

function createGame(config) {

    let str = '';
    for (i = 1; i <= config.number_of_lines; i++) {
        str += "<tr>";
        for (j = 1; j <= config.number_of_columns; j++) {
            str += '<td>'
            str += '<a id="' + i + '_' + j + '" class="sprite"></a>';
            str += '</td>';
        }
        str += ' </tr>';
    }

    return str;
}

/////////////////////////////////////////////////////////////////////
////////////////////////// App main
/////////////////////////////////////////////////////////////////////

$(document).ready(function () {

    let progressBar = $('.progress-bar');
    let startBtn = $('#start-btn');

    let session_id = null;
    let config = null;
    let timer = null;

    listGameUpdate();

    startBtn.click(function (event) {
        $(this).prop("disabled", true);

        startGame().done(function (json) {
            session_id = json.session_id;
            config = json.config;

            $('#board table tbody').html(createGame(config));

            let duration = 60 * config.time_limit;
            timer = startTimer(duration, { 'progressBar': progressBar, 'startBtn': startBtn }, session_id);

            $("a").click(function (event) {

                event.preventDefault();

                let pline = $(this).attr("id").split("_")[0];
                let pcolumn = $(this).attr("id").split("_")[1];
                let me = $(this);
                me.css("pointer-events", "none");

                playGame(pline, pcolumn, session_id)
                    .done(function (json) {

                        let position = json.result.position;
                        let previous = json.result.previous;
                        let find = json.result.find;
                        let win = json.result.win;

                        me.addClass(position.image);

                        if (win) {
                            stopTimer(timer, { 'progressBar': progressBar, 'startBtn': startBtn });
                            stoptGame(session_id).done(function (json) {
                                alert("you win !!!!!!!!!");
                                listGameUpdate();
                            });
                        }
                        else if (previous) {
                            if (!find) {
                                let l = json.result.previous.pline;
                                let c = json.result.previous.pcolumn;
                                let id = "#" + l + "_" + c;
                                setTimeout(function () {
                                    $(id).removeClass(previous.image);
                                    me.removeClass(position.image);

                                    $(id).css("pointer-events", "initial");
                                    me.css("pointer-events", "initial");
                                }, 700);

                            }
                        }
                    });
            });


        });



    });



});