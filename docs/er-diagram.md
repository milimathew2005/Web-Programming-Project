# Library Book Reservation & Fine Tracker - Entity Relationship Diagram

This document contains the complete database schema design and Entity-Relationship (ER) diagram for the Library Book Reservation and Fine Tracker application built with Laravel.

## Mermaid ER Diagram

```mermaid
erDiagram
    USERS ||--o{ RESERVATIONS : "places"
    USERS ||--o{ LOANS : "borrows"
    CATEGORIES ||--o{ BOOKS : "classifies"
    BOOKS ||--o{ BOOK_COPIES : "contains"
    BOOK_COPIES ||--o{ RESERVATIONS : "reserved_in"
    BOOK_COPIES ||--o{ LOANS : "borrowed_in"
    RESERVATIONS ||--o| LOANS : "converted_to"

    USERS {
        bigint id PK
        string name
        string email UK
        enum role "student | librarian"
        string student_id UK "nullable"
        timestamp email_verified_at
        string password
        string remember_token
        timestamps
    }

    CATEGORIES {
        bigint id PK
        string name UK
        timestamps
    }

    BOOKS {
        bigint id PK
        string title
        string author
        string isbn UK
        bigint category_id FK
        string publisher
        int published_year
        text description "nullable"
        string cover_image "nullable"
        timestamps
    }

    BOOK_COPIES {
        bigint id PK
        bigint book_id FK
        string copy_code UK
        enum status "available | reserved | borrowed | lost"
        timestamps
    }

    RESERVATIONS {
        bigint id PK
        bigint user_id FK
        bigint book_copy_id FK
        enum status "pending | collected | cancelled | expired"
        timestamp reserved_at
        timestamp expires_at
        timestamps
    }

    LOANS {
        bigint id PK
        bigint user_id FK
        bigint book_copy_id FK
        bigint reservation_id FK "nullable"
        timestamp issued_at
        timestamp due_date
        timestamp returned_at "nullable"
        decimal fine_amount "8,2"
        boolean fine_paid
        timestamps
    }
```

## Entity Descriptions & Business Rules

1. **`users`**: Represents students and librarians.
   - `role`: Enum (`'student'`, `'librarian'`). Default is `'student'`.
   - `student_id`: Unique student identifier string (e.g., `STU001`).

2. **`categories`**: Book subject categories (e.g., Computer Science, Literature). Unique category names.

3. **`books`**: Core book catalog entries with metadata (ISBN, Author, Publisher, Year, Description).

4. **`book_copies`**: Individual physical or digital copies of a book.
   - `copy_code`: Unique barcode/tracking code (e.g., `BK1001-C1`).
   - `status`: Enum (`'available'`, `'reserved'`, `'borrowed'`, `'lost'`). Default is `'available'`.

5. **`reservations`**: Online book reservation queue entries.
   - `status`: Enum (`'pending'`, `'collected'`, `'cancelled'`, `'expired'`).
   - `reserved_at` & `expires_at`: Timestamp window (default 48 hours expiry).

6. **`loans`**: Book checkout and circulation records.
   - `issued_at` & `due_date`: Circulation duration (default 14 days).
   - `returned_at`: Null while book is checked out, set upon return.
   - `fine_amount` & `fine_paid`: Overdue charges calculated per day past due date.
