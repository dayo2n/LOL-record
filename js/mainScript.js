var modal_login = document.getElementById("modal_login"); //로그인 모달 창
var modal_join = document.getElementById("modal_join"); // 회원가입 모달창

var login_btn = document.getElementById("login-btn"); // 메인 화면의 로그인 버튼
var join_btn = document.getElementById("join-btn"); // 메인 화면의 가입 버튼

var login = document.getElementById("login"); // 로그인 모달 창 내의 로그인 버튼
var join = document.getElementById("join"); // 회원가입 모달 창 내의 가입 버튼
var cancelLogin = document.getElementById("cancelLogin");
var cancelJoin = document.getElementById("cancelJoin");

//모달창을 띄우는 코드
login_btn.addEventListener("click", function () {
  modal_login.style.display = "block";
  join_btn.disabled = true;
});
join_btn.addEventListener("click", function () {
  modal_join.style.display = "block";
  login_btn.disabled = true;
});

login.addEventListener("click", function () {
  //로그인 버튼 누르면 validation 검사 후 충족 시 로그인 화면
});

join.addEventListener("click", function () {
  //회원가입 버튼 누르면 validation 검사 후 충족 시 member.json에 정보 추가
});

//모달창에서 취소버튼 누르면 닫고 칸 비우고 버튼 활성화
cancelLogin.addEventListener("click", function () {
  document.getElementById("memberID").value = "";
  document.getElementById("memberPW").value = "";
  modal_login.style.display = "none";
  join_btn.disabled = false;
});

cancelJoin.addEventListener("click", function () {
  document.getElementById("memberID").value = "";
  document.getElementById("memberPW").value = "";
  modal_join.style.display = "none";
  login_btn.disabled = false;
});
