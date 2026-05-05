# 🎓 Viva Voce: Real Estate Platform

This guide contains common viva questions and answers tailored to this project's architecture and technology stack.

---

## 🌐 1. Web Fundamentals & Frontend

**Q1: What is the overall tech stack used in this project?**
- **Answer**: The project uses **HTML5** for structure, **CSS3** for styling, **JavaScript** for client-side interactions, **PHP** for server-side logic, and **MySQL** as the relational database.

**Q2: Is this website responsive? How was it achieved?**
- **Answer**: Yes, the website uses **CSS Flexbox** and **Grid** along with media queries to adapt to different screen sizes.

**Q3: What is the purpose of the `onerror` attribute used in the property images?**
- **Answer**: It is a fallback mechanism. If a property image is missing on the server, it automatically loads a placeholder image via the `onerror` JavaScript event.

---

## 🐘 2. PHP & Backend Logic

**Q4: What is PDO (PHP Data Objects) and why did you use it?**
- **Answer**: PDO is a database abstraction layer. It provides a consistent interface for database access and, most importantly, supports **Prepared Statements** to prevent **SQL Injection**.

**Q5: How do you handle user authentication and sessions?**
- **Answer**: We use PHP Sessions (`$_SESSION`). Upon successful login, user data is stored in the session, which is verified on every protected page using `session_start()`.

**Q6: How do you protect user passwords in the database?**
- **Answer**: We use `password_hash()` with the BCRYPT algorithm. This ensures that even if the database is leaked, the actual passwords cannot be easily deciphered.

---

## 🗄️ 3. Database & MySQL

**Q7: Explain the relationship between the `users` and `properties` tables.**
- **Answer**: It is a **One-to-Many** relationship. One user can list multiple properties. The `properties` table contains a `user_id` as a foreign key.

**Q8: What does `ON DELETE CASCADE` do in your schema?**
- **Answer**: It maintains referential integrity. When a property is deleted, all its associated images and amenities are automatically deleted by the database.

**Q9: Why is the search city dropdown dynamic?**
- **Answer**: The cities are fetched using `SELECT DISTINCT city FROM locations`. This allows the platform to support any city without needing code changes.

---

## 🛡️ 4. Security & Best Practices

**Q10: How do you prevent SQL Injection?**
- **Answer**: By using prepared statements with placeholders (e.g., `prepare("SELECT * FROM users WHERE email = ?")`). The database treats the input as data, not as executable code.

**Q11: What is the benefit of the `include` and `require_once` statements?**
- **Answer**: They promote code reusability. Common elements like the header, footer, and database connection are kept in separate files and included wherever needed.

---

## 🚀 5. Advanced Questions

**Q12: How would you handle image uploads securely?**
- **Answer**: 
  1. Check file extensions (e.g., only JPG/PNG).
  2. Rename files using `uniqid()` to prevent overwriting and directory traversal attacks.
  3. Limit the file size.

**Q13: How does the admin distinguish between a regular user and themselves?**
- **Answer**: By checking the `role` column in the session (`$_SESSION['role']`). If it's not 'admin', access to the admin dashboard is denied.
