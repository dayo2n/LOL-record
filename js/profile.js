// $userName = "아침밥은안챙겨요";

// $.ajax({
//   url: "https://kr.api.riotgames.com/lol/summoner/v4/summoners/by-name/",
//   data: { summonerName: $userName },
//   method: "GET",
//   dataType: "json",
// })
//   .done(function (json) {
//     $("<h1>").text(json.title).appendTo("body");
//     $('<div class="content">').html(json.html).appendTo("body");
//   })

//   .faul(function (xhr, status, errorThrown) {
//     $("#text")
//       .html("error!<br>")
//       .append("error code : " + errorThrown + "<br>")
//       .append("status : " + status);
//   })

//   .always(function (xhr, status) {
//     $("#text").html("success");
//   });
