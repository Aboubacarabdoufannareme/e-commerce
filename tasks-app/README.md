# Tasks CRUD App

A simple PHP + MySQL task management application built for the E-Commerce Lab (Lab 01).

## Features
- Create, Read, Update, Delete tasks
- Task status: pending / in_progress / done
- Built with PHP (mysqli), MySQL, HTML

## Setup
1. Copy `db.example.php` to `db.php`
2. Fill in your database credentials in `db.php`
3. Visit `setup.php` once to create the `tasks` table
4. Open `index.php` to use the app

## Files
- `db.php` — database connection (not committed — see `db.example.php`)
- `setup.php` — one-time table creation
- `index.php` — list all tasks (Read)
- `create.php` — add a task (Create)
- `edit.php` — edit a task (Update)
- `delete.php` — delete a task (Delete)

## Live Demo
http://169.239.251.102:442/~fannareme.abdou/tasks-app/