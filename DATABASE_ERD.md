# Database Entity-Relationship Diagram (ERD) & Schemas

## 1. Mermaid Entity-Relationship Diagram

```mermaid
erDiagram
    roles ||--o{ users : "assigns"
    users ||--o{ meetings : "creates"
    users ||--o{ attendances : "records"
    users ||--o{ task_assignees : "assigned_to"
    users ||--o{ task_submissions : "submits"
    users ||--o{ task_activities : "logs"
    users ||--o{ notifications : "receives"
    users ||--o{ audit_logs : "records"

    meetings ||--o{ attendances : "contains"
    
    task_statuses ||--o{ tasks : "defines_status"
    task_priorities ||--o{ tasks : "defines_priority"
    
    tasks ||--o{ task_assignees : "assigned_members"
    tasks ||--o{ task_labels : "tagged_with"
    tasks ||--o{ task_submissions : "receives_submissions"
    tasks ||--o{ task_activities : "history_timeline"
    
    labels ||--o{ task_labels : "tag_association"
    task_statuses ||--o{ task_submissions : "submission_status"

    roles {
        int id PK
        string name
        string slug UK
        string description
    }

    users {
        int id PK
        string member_uuid UK "Indexed"
        int role_id FK "Indexed"
        string username UK "Indexed"
        string email UK "Indexed"
        string password_hash
        string full_name
        string nis_nip
        string class_dept
        string phone
        string avatar
        int qr_version
        datetime qr_updated_at
        string status "Indexed"
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }

    meetings {
        int id PK
        string uuid UK "Indexed"
        string title
        text description
        text learning_material
        string mentor
        string location
        date meeting_date "Indexed"
        time start_time
        time end_time
        string qr_token "Indexed"
        string pin_code "Indexed"
        string status "Indexed"
        int created_by FK
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }

    attendances {
        int id PK
        int meeting_id FK "Indexed"
        int user_id FK "Indexed"
        string method "meeting_qr, member_qr, manual, pin"
        int scanned_by_admin_id FK
        datetime scan_time
        string status "present, late, sick, permitted, alpha - Indexed"
        text notes
        string device
        string ip_address
    }

    task_statuses {
        int id PK
        string name
        string color
        int sort_order
    }

    task_priorities {
        int id PK
        string name
        string color
        int sort_order
    }

    labels {
        int id PK
        string name
        string color
    }

    tasks {
        int id PK
        string uuid UK "Indexed"
        string title
        text description
        int priority_id FK "Indexed"
        int status_id FK "Indexed"
        datetime deadline "Indexed"
        int created_by FK "Indexed"
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }

    task_assignees {
        int id PK
        int task_id FK "Indexed"
        int user_id FK "Indexed"
        datetime assigned_at
    }

    task_labels {
        int task_id PK, FK
        int label_id PK, FK
    }

    task_submissions {
        int id PK
        int task_id FK "Indexed"
        int user_id FK "Indexed"
        text submission_text
        string attachment_url
        int status_id FK "Indexed"
        text feedback
        int grade
        int evaluated_by FK
        datetime submitted_at
        datetime updated_at
    }

    task_activities {
        int id PK
        int task_id FK "Indexed"
        int user_id FK
        string action
        text description
        datetime created_at
    }

    notifications {
        int id PK
        int user_id FK "Indexed"
        string title
        text message
        string type
        tinyint is_read "Indexed"
        datetime created_at
    }

    audit_logs {
        int id PK
        int user_id FK "Indexed"
        string action "Indexed"
        text description
        string ip_address
        string user_agent
        datetime created_at
    }

    settings {
        int id PK
        string setting_key UK
        text setting_value
        datetime updated_at
    }
```

---

## 2. Indexing Strategy Summary

1. **`users` Table**:
   - `member_uuid` (UNIQUE INDEX)
   - `username` (UNIQUE INDEX)
   - `email` (UNIQUE INDEX)
   - `role_id` (INDEX)
   - `status` (INDEX)

2. **`meetings` Table**:
   - `uuid` (UNIQUE INDEX)
   - `qr_token` (INDEX)
   - `pin_code` (INDEX)
   - `meeting_date` (INDEX)
   - `status` (INDEX)

3. **`attendances` Table**:
   - `meeting_id` (INDEX)
   - `user_id` (INDEX)
   - `status` (INDEX)
   - Composite Index: `(meeting_id, user_id)` for duplicate check optimization.

4. **`tasks` Table**:
   - `uuid` (UNIQUE INDEX)
   - `priority_id` (INDEX)
   - `status_id` (INDEX)
   - `deadline` (INDEX)
   - `created_by` (INDEX)

5. **`task_assignees` Table**:
   - Composite Index / Keys: `(task_id, user_id)`

6. **`notifications` Table**:
   - `user_id` (INDEX)
   - `is_read` (INDEX)
