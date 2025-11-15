// API 엔드포인트
const API_URL = 'http://localhost:8080/api.php';

// DOM 요소
const todoForm = document.getElementById('todoForm');
const taskInput = document.getElementById('taskInput');
const todoList = document.getElementById('todoList');
const statsDiv = document.getElementById('stats');

// 페이지 로드 시 할 일 목록 가져오기
document.addEventListener('DOMContentLoaded', loadTodos);

// 폼 제출 이벤트
todoForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const task = taskInput.value.trim();

    if (task) {
        await addTodo(task);
        taskInput.value = '';
        await loadTodos();
    }
});

// 할 일 목록 불러오기
async function loadTodos() {
    try {
        const response = await fetch(API_URL);
        const todos = await response.json();
        renderTodos(todos);
    } catch (error) {
        console.error('할 일 목록 로드 실패:', error);
    }
}

// 할 일 추가
async function addTodo(task) {
    try {
        await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ task })
        });
    } catch (error) {
        console.error('할 일 추가 실패:', error);
    }
}

// 할 일 완료 상태 토글
async function toggleTodo(id) {
    try {
        await fetch(API_URL, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id })
        });
        await loadTodos();
    } catch (error) {
        console.error('할 일 상태 변경 실패:', error);
    }
}

// 할 일 삭제
async function deleteTodo(id) {
    if (confirm('정말 삭제하시겠습니까?')) {
        try {
            await fetch(`${API_URL}?id=${id}`, {
                method: 'DELETE'
            });
            await loadTodos();
        } catch (error) {
            console.error('할 일 삭제 실패:', error);
        }
    }
}

// 할 일 목록 렌더링
function renderTodos(todos) {
    if (todos.length === 0) {
        todoList.innerHTML = `
            <div class="empty-state">
                <p>아직 할 일이 없습니다.</p>
                <p>위에서 새로운 할 일을 추가해보세요!</p>
            </div>
        `;
        statsDiv.style.display = 'none';
        return;
    }

    todoList.innerHTML = todos.map(todo => `
        <li class="todo-item ${todo.completed ? 'completed' : ''}">
            <div class="checkbox ${todo.completed ? 'checked' : ''}"
                 onclick="toggleTodo(${todo.id})">
            </div>
            <span class="todo-text">${escapeHtml(todo.task)}</span>
            <button class="delete-btn" onclick="deleteTodo(${todo.id})">삭제</button>
        </li>
    `).join('');

    // 통계 업데이트
    const total = todos.length;
    const completed = todos.filter(todo => todo.completed).length;
    const remaining = total - completed;

    statsDiv.innerHTML = `전체 ${total}개 | 완료 ${completed}개 | 남은 할 일 ${remaining}개`;
    statsDiv.style.display = 'block';
}

// XSS 방지를 위한 HTML 이스케이프
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
