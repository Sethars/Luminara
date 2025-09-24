import { getQueryParam } from "../module_js/get_query.js";

const id = getQueryParam('id');

document.addEventListener('DOMContentLoaded', async function () {
  try{
    const res = await fetch('api/getAnotherUserProfileData', {
      method: "POST",
      headers: {
        Authorization : `Bearer ${token}`
      },
      body: JSON.stringify({id})
    })
    const data = await res.json();
    if(data.success){
      //Ambil data
      const user = data['user'];
      const username = user.username;
      const createdAt = user.created_at
      const bio = user.bio;
      const badges = user.badges
      const chip = user.chip
      const cash = user.cash
      const photo = user.photo
      const gender = user.gender
      const isVip = user.isVip
      const win = user.win
      const lose = user.lose
      const totalMatches = user.total_matches
      const winRate = user.win_rate
      const loseRate = totalMatches > 0 ? 100 - winRate : 0;
      const comments = user.comments;

      //Functions

      //Profile Photo
      document.getElementById('profile-photo').src = photo ?  photo : '/assets/img/photo_profile/ppkosong.jpg'

      //Username
      document.getElementById('profile-username').textContent = username;

      //Gender
      if(gender === "Male"){
        document.getElementById('profile-gender').classList.add('bi-gender-male');
        document.getElementById('profile-gender').style.color = 'blue';
      } else if(gender === "Female") {
        document.getElementById('profile-gender').classList.add('bi-gender-female');
        document.getElementById('profile-gender').style.color = 'pink';
      } else {
        document.getElementById('profile-gender').classList.add('bi-crosshair');
        document.getElementById('profile-gender').style.color = 'red';
      }

      //Created At
      document.getElementById('createdAt').textContent = createdAt;

      //Bio
      let bioContent = bio;
      if(bio === null){
        bioContent = 'Pengguna belum mengatur bio';
      }
      document.getElementById('profile-bio').textContent = bioContent;

      //Total Matches
      document.getElementById('total-matches').textContent = totalMatches;

      //Win Rate
      document.getElementById('win-rate').textContent = winRate + '%';

      //Chart winrate
      const ctx = document.getElementById("winRateChart").getContext("2d");
      const winRateChart = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: ["Kemenangan", "Kekalahan"],
          datasets: [
            {
              data: [winRate, loseRate],
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
                  let total = context.chart._metasets[context.datasetIndex].total; 
                  let value = context.raw;
                  let percentage = ((value / total) * 100).toFixed(2); // fix 2 desimal
                  return context.label + ": " + percentage + "%";
                },
              },
            },
          },
        },
      });

      //Badges
      const badgesContainer = document.querySelector('.badges-container');
      badgesContainer.innerHTML = "";

      const allBadges = [...badges.used, ...badges.unused];

      allBadges.forEach(badgeName => {
        const span = document.createElement("span");
        span.className = `badge ${badgeStyles[badgeName] || "bg-dark text-white"} px-2 py-1`;
        span.innerHTML = `<i class="${
          badgeIcons[badgeName] || "fa-solid fa-star"
        } me-1"></i>${underscoreDelete(badgeName)}`;
        span.style.fontSize = '1rem'
        badgesContainer.appendChild(span);
      })

      //Comments List
      const commentList = document.querySelector('.comments-list');

      comments.forEach(data => {
        commentList.innerHTML +=`
          <div class="comment">
            <div class="comment-header">
              <div class="comment-avatar">
                <img src="${data.commenter_photo}">
              </div>
              <div class="comment-user">${data.commenter_username}</div>
              <div class="comment-date">${data.created_at}</div>
            </div>
            <div class="comment-text">
              ${data.comment}
            </div>
          </div>
        `
      });

      //Add Comment
      document.getElementById('submit-comment').addEventListener('click', async function (e) {
        e.preventDefault();

        const comment = document.getElementById('comment');
        const user = JSON.parse(localStorage.getItem('user'))
        const profile = JSON.parse(localStorage.getItem('profile'))
        const msg = document.getElementById('comment-message');

        msg.textContent = ''
        msg.className = ''

        try{
          const res = await fetch('api/addComment', {
            method: "POST",
            headers: {
              "Content-Type" : "application/json",
              Authorization : `Bearer ${token}`
            },
            body: JSON.stringify({id, comment: comment.value})
          })

          const result = await res.json();

          if(result.success){
            msg.textContent = result.message;
            msg.classList.add('text-success');
            commentList.insertAdjacentHTML ('afterbegin', `
            <div class="comment">
              <div class="comment-header">
                <div class="comment-avatar">
                  <img src="${profile.photo}">
                </div>
                <div class="comment-user">${user.username}</div>
                <div class="comment-date">Baru saja</div>
              </div>
              <div class="comment-text">
                ${comment.value}
              </div>
            </div>
          `);
          comment.value = '';
          }
        } catch (err){
          console.error(err);
          msg.textContent = result.message;
          msg.classList.add('text-danger');
          if(result.error) console.error(result.error);
        }
      })
    } else {
      if(data.redirect) {
        window.location.href = '/profile';
        return;
      }

      console.error('ERROR: ' + data.message)
      if(data.error) console.error(data.error);
      window.location.href = '/';
    }
  } catch (err){
    console.error(err);
    return null;
  }
});
