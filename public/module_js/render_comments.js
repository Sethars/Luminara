export function renderComments(container, comments, canDelete) {
  container.innerHTML = "";

  if (comments.length == 0) {
    container.innerHTML = `
            <div class="comment">
                <div class="comment-text d-flex justify-content-center text-muted">
                    <strong>Tidak ada komentar</strong>
                </div>
            </div>
        `;
    return;
  }

  comments.forEach((data) => {
    container.innerHTML += `
            <div class="comment">
                <div class="comment-header">
                    <div class="comment-avatar">
                    <img src="${data.commenter_photo}">
                    </div>
                    <div class="comment-user">${data.commenter_username}</div>
                    <div class="comment-date">${data.created_at}</div>
                    ${
                      canDelete
                        ? `
                        <div class="comment-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                    `
                        : ""
                    }
                </div>
                <div class="comment-text">
                    ${data.comment}
                </div>
                ${
                  canDelete
                    ? `
                    <div class="comment-actions">
                        <button class="btn-danger delete-comment" data-value="${
                          data.id || ""
                        }">Hapus</button>
                    </div>
                `
                    : ""
                }
            </div>
        `;
  });
}
