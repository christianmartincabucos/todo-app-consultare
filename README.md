# Todo Application

This is a Todo Application built with Laravel 11 for the backend API and Vue 3 for the frontend.

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL or another database supported by Laravel

## Backend Setup

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/todo-app-consultare.git
   cd todo-app-consultare
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Copy the `.env.example` file to `.env` and configure your database settings:
   ```
   cp .env.example .env
   ```

4. Generate an application key:
   ```
   php artisan key:generate
   ```

5. Run database migrations:
   ```
   php artisan migrate
   ```

6. Start the Laravel development server:
   ```
   php artisan serve
   ```

## Frontend Setup

1. Navigate to the frontend directory:
   ```
   cd frontend
   ```

2. Install npm dependencies:
   ```
   npm install
   ```

3. Start the Vue development server:
   ```
   npm run dev
   ```

4. Open your browser and navigate to `http://localhost:5173` to view the application.

## Usage

- Add new tasks using the form at the top of the page.
- Mark tasks as completed or pending by clicking the respective buttons.
- Edit task titles by clicking the "Edit" button.
- Delete tasks by clicking the "Delete" button.
- Filter tasks by their status using the dropdown menu.

## API Endpoints

- GET /api/tasks: Retrieve all tasks
- POST /api/tasks: Create a new task
- PUT /api/tasks/{id}: Update an existing task
- DELETE /api/tasks/{id}: Delete a task

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.