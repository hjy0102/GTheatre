$(function () {

    $(document).ready(function() {
        $.ajax({
            url: "/showtimes/populate-movies",
            type: "GET",

            success: function (data) {
                console.log(data);
                var selector = $("#select-movie");
                populateHelper(JSON.parse(data), selector, "Title");
                populateMovies(JSON.parse(data));
            },
            error: function() {
                alert("Something failed");
            }
        });

        $.ajax({
            url: "/showtimes/populate-halls",
            type: "GET",

            success: function (data) {
                console.log(data);
                populateHalls(JSON.parse(data));
            },
            error: function() {
                alert("Something failed");
            }
        });
    });

    function populateMovies(data) {
        var selector = $("#showcards");
        selector.empty();
        for (var i = 0; i < data.length; i++) {
            selector.append("<div class='well showcards'>" + JSON.stringify(data[i]) + "</div>");
        }        
    }

    function populateHelper(data, selector, key) {
        selector.empty();
        selector.append('<option></option>'); //blank option to "deselect"
        for (var i = 0; i < data.length; i++) {
            selector.append('<option>' + data[i][key] + '</option>');
        }
        selector.selectpicker("refresh");
    }

    function populateHalls(data) {
        var selector = $("#hall");
        selector.empty();
        for (var i = 0; i < data.length; i++) {
            selector.append("<option data-subtext='Capacity " + data[i].Capacity + " seats'>" + data[i].HNumber + "</option>");
        }
        selector.selectpicker("refresh");
    }
    
    var numStartTimes = 1;
    $("#addstarttime").click(function() {
        var selector = $("#starttimes");
        selector.append("<div class='form-group'><div><input type='time' class='form-control' id='datetime" + numStartTimes++ + "' required></div></div>")
        $("#addMovieForm").validator("update"); //tell bootstrap form validator that inputs have changed
    });

    /**
     * This function clears all inputs on modal close or click cancel
     */
    $("#addMovie").on("hidden.bs.modal", function(){
        var selector = $("#addMovieForm")
        selector.validator("destroy");
        $(this).find("input, select").not(".btn").val(""); //selects all text inputs except for inputs disguised as buttons and clears
        $(this).find(".selectpicker").selectpicker("refresh"); //must refresh bootstrap selectors after changing selected value
        $("#starttimes").empty(); //clear all the additional starttimes
        numStartTimes = 1; //reset counter
        $("#addMovieForm").validator("update"); //tell bootstrap form validator that inputs have changed
    });

    
});