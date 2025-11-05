function showVideo() {
  let link = document.getElementById("ytLink").value.trim();

  // Chuyển mọi link YouTube sang dạng nhúng hợp lệ
  if (link.includes("watch?v=")) {
    link = link.replace("watch?v=", "embed/");
  } else if (link.includes("youtu.be/")) {
    const id = link.split("youtu.be/")[1];
    link = "https://www.youtube.com/embed/" + id;
  }

  // Gán iframe vào thẻ có class "video"
  const videoBox = document.querySelector(".video");
  videoBox.innerHTML = `<iframe src="${link}" allowfullscreen></iframe>`;
}
