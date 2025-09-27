// export function renderComments(container, comments, canDelete) {
//   container.innerHTML = "";

//   if (comments.length == 0) {
//     container.innerHTML = `
//             <div class="comment">
//                 <div class="comment-text d-flex justify-content-center text-muted">
//                     <strong>Tidak ada komentar</strong>
//                 </div>
//             </div>
//         `;
//     return;
//   }

//   comments.forEach((data) => {
//     container.innerHTML += `
//             <div class="comment">
//                 <div class="comment-header">
//                     <div class="comment-avatar">
//                     <img src="${data.commenter_photo}">
//                     </div>
//                     <div class="comment-user">${data.commenter_username}</div>
//                     <div class="comment-date">${data.created_at}</div>
//                     ${
//                       canDelete
//                         ? `
//                         <div class="comment-menu">
//                             <i class="fas fa-ellipsis-v"></i>
//                         </div>
//                     `
//                         : ""
//                     }
//                 </div>
//                 <div class="comment-text">
//                     ${data.comment}
//                 </div>
//                 ${
//                   canDelete
//                     ? `
//                     <div class="comment-actions">
//                         <button class="btn-danger delete-comment" data-value="${
//                           data.id || ""
//                         }">Hapus</button>
//                     </div>
//                 `
//                     : ""
//                 }
//             </div>
//         `;
//   });
// }

export function renderComments(container, comments, canDelete) {
  container.innerHTML = "";

  if (comments.length === 0) {
    const div = document.createElement("div");
    div.className = "comment";

    const inner = document.createElement("div");
    inner.className = "comment-text d-flex justify-content-center text-muted";

    const strong = document.createElement("strong");
    strong.textContent = "Tidak ada komentar";

    inner.appendChild(strong);
    div.appendChild(inner);
    container.appendChild(div);
    return;
  }

  comments.forEach((data) => {
    const comment = document.createElement("div");
    comment.className = "comment";

    // HEADER
    const header = document.createElement("div");
    header.className = "comment-header";

    // Avatar
    const avatar = document.createElement("div");
    avatar.className = "comment-avatar";

    const img = document.createElement("img");
    img.setAttribute("src", data.commenter_photo || "default.png");
    img.setAttribute("alt", "avatar");
    avatar.appendChild(img);

    // Username
    const user = document.createElement("div");
    user.className = "comment-user";
    user.textContent = data.commenter_username || "Anonim";

    // Date
    const date = document.createElement("div");
    date.className = "comment-date";
    date.textContent = data.created_at || "";

    header.appendChild(avatar);
    header.appendChild(user);
    header.appendChild(date);

    // Menu (jika bisa delete)
    if (canDelete) {
      const menu = document.createElement("div");
      menu.className = "comment-menu";
      const icon = document.createElement("i");
      icon.className = "fas fa-ellipsis-v";
      menu.appendChild(icon);
      header.appendChild(menu);
    }

    comment.appendChild(header);

    // TEXT
    const text = document.createElement("div");
    text.className = "comment-text";
    text.textContent = data.comment || "";
    comment.appendChild(text);

    // ACTIONS (jika bisa delete)
    if (canDelete) {
      const actions = document.createElement("div");
      actions.className = "comment-actions";

      const btn = document.createElement("button");
      btn.className = "btn-danger delete-comment";
      btn.dataset.value = data.id || "";
      btn.textContent = "Hapus";

      actions.appendChild(btn);
      comment.appendChild(actions);
    }

    container.appendChild(comment);
  });
}
