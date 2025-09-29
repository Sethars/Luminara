import { fetchWithAuth } from "../module_js/fetch_with_auth.js";
import { formatMoney } from "../module_js/format_money.js";
import { getQueryParam } from "../module_js/get_query.js";
import { renderComments } from "../module_js/render_comments.js";

const id = getQueryParam('id');

document.addEventListener('DOMContentLoaded', async function () {
  try{
    const res = await fetchWithAuth('api/getAnotherUserProfileData', {
      method: "POST",
      body: JSON.stringify({id})
    })
    const data = await res.json();
    if(data.success){
      //Ambil data
      const users = data['user'];
      const username = users.username;
      const createdAt = users.created_at
      const bio = users.bio;
      const badges = users.badges
      const cash = users.cash
      const photo = users.photo
      const gender = users.gender
      const isVip = users.isVip
      const win = users.win
      const lose = users.lose
      const totalMatches = users.total_matches
      const winRate = users.win_rate
      const loseRate = totalMatches > 0 ? 100 - winRate : 0;
      const comments = users.comments;

      //Functions

      //Profile Photo
      document.getElementById('profile-photo').src = photo ?  photo : '/assets/img/photo_profile/ppkosong.jpg'

      //Username
      document.getElementById('profile-username').textContent = username;
      
      //Cash
      document.getElementById('profile-cash').textContent = formatMoney(cash); 

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
          labels: [`Kemenangan: ${win}`, `Kekalahan: ${lose}`],
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
      renderPreviewBadges('.badges-container', [...badges.used, ...badges.unused], "Tidak memiliki badge")

      //Comments List
      const commentList = document.querySelector('.comments-list');
      renderComments(commentList, comments, false)

      //Add Comment
      document.getElementById('submit-comment').addEventListener('click', async function (e) {
        e.preventDefault();

        const comment = document.getElementById('comment');
        const msg = document.getElementById('comment-message');

        msg.textContent = ''
        msg.className = ''

        try{
          const res = await fetchWithAuth('api/addComment', {
            method: "POST",
            headers: {
              "Content-Type" : "application/json"
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
