<?php

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>

    <link rel="stylesheet" type="text/css" href="/assets/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>


</head>
<body>
<div class="searching-div">
    <div class="search-box">
        <input type="text" name='serching' placeholder="Type to search..">
        <div class="search-icon">
            <i class="fas fa-search"></i>
        </div>
        <div class="cancel-icon">
            <i class="fas fa-times"></i>
        </div>
        <div class="search-data"></div>
    </div>
</div>
<div class="result-div"></div>
<div class="no-result"></div>
<script>
    const searchBox = document.querySelector(".search-box");
    const searchBtn = document.querySelector(".search-icon");
    const cancelBtn = document.querySelector(".cancel-icon");
    const searchInput = document.querySelector("input");
    const searchData = document.querySelector(".search-data");
    const resultData = document.querySelector(".result-div");
    const noData = document.querySelector(".no-result");
    searchBtn.onclick = () => {
        searchBox.classList.add("active");
        searchBtn.classList.add("active");
        searchInput.classList.add("active");
        cancelBtn.classList.add("active");
        searchInput.focus();
        const values = searchInput.value;
        searchData.classList.remove("active");
        resultData.innerHTML = '';
        gettingData(values)
        searchData.innerHTML = "You just typed " + "<span style='font-weight: 500;'>" + values + "</span>";
        searchData.textContent = "";
    }

    const gettingData = (values) => {
        fetch("/Authors?q=" + values).then(response => {
            return response.json()
        })
            .then((data) => {
                if (data.length > 0) {
                    data.map((d) => {
                        resultData.innerHTML += ` <div class="card">
                                      <div class="left">
                                       ${d.name}
                                            </div>
                                     <div class="right">
                                    ${d.book_name}
                                    </div>
                                       </div>`;
                    })
                } else {
                    noData.innerHTML = "You just typed. " + "<span style='font-weight: 500;'>" + " '" + values + "' " + " There no result. Please try again. " + "</span>";
                }

            })
    }

    cancelBtn.onclick = () => {
        searchBox.classList.remove("active");
        searchBtn.classList.remove("active");
        searchInput.classList.remove("active");
        cancelBtn.classList.remove("active");
        searchData.classList.toggle("active");
        searchInput.value = "";
        noData.innerHTML = "";
    }

    searchInput.addEventListener('keyup', function onEvent(e) {
        if (e.keyCode === 13) {
            resultData.innerHTML = '';
            noData.innerHTML = "";
            const values = searchInput.value;
            gettingData(values)

        }
    });


</script>
</body>
</html>