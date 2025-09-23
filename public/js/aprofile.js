const ctx = document.getElementById("winRateChart").getContext("2d");
const winRateChart = new Chart(ctx, {
  type: "doughnut",
  data: {
    labels: ["Kemenangan", "Kekalahan"],
    datasets: [
      {
        data: [49.51, 50.49],
        backgroundColor: [
          "rgba(52, 152, 219, 0.8)",
          "rgba(189, 195, 199, 0.8)",
        ],
        borderColor: ["rgba(52, 152, 219, 1)", "rgba(189, 195, 199, 1)"],
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: "bottom",
        labels: {
          font: {
            size: 14,
          },
          padding: 15,
        },
      },
      title: {
        display: true,
        text: "Win Rate Diagram",
        font: {
          size: 16,
        },
        padding: {
          top: 10,
          bottom: 15,
        },
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            return context.label + ": " + context.raw.toFixed(2) + "%";
          },
        },
      },
    },
  },
});

// Comment submission
document
  .getElementById("submit-comment")
  .addEventListener("click", function () {
    const textarea = document.querySelector(".comment-form textarea");
    const commentText = textarea.value.trim();

    if (commentText) {
      const commentsList = document.querySelector(".comments-list");
      const newComment = document.createElement("div");
      newComment.className = "comment";

      const currentDate = new Date();
      const formattedDate = "Baru saja";

      newComment.innerHTML = `
                    <div class="comment-header">
                        <div class="comment-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="comment-user">Anda</div>
                        <div class="comment-date">${formattedDate}</div>
                    </div>
                    <div class="comment-text">
                        ${commentText}
                    </div>
                `;

      commentsList.insertBefore(newComment, commentsList.firstChild);
      textarea.value = "";
    }
  });
