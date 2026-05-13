CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime
  ,
  "uuid" varchar not null
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_expiration_index" on "cache"("expiration");
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_locks_expiration_index" on "cache_locks"("expiration");
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "categories"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "uuid" varchar not null,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE INDEX "categories_user_id_index" on "categories"("user_id");
CREATE UNIQUE INDEX "users_uuid_unique" on "users"("uuid");
CREATE UNIQUE INDEX "categories_uuid_unique" on "categories"("uuid");
CREATE TABLE IF NOT EXISTS "tasks"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "category_id" integer,
  "title" varchar not null,
  "description" text,
  "is_recurring" tinyint(1) not null default('0'),
  "task_date" datetime,
  "completed_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  "uuid" varchar not null,
  "recurring_task_id" integer,
  foreign key("category_id") references categories("id") on delete set null on update no action,
  foreign key("user_id") references users("id") on delete cascade on update no action,
  foreign key("recurring_task_id") references "recurring_tasks"("id")
);
CREATE INDEX "tasks_user_id_category_id_index" on "tasks"(
  "user_id",
  "category_id"
);
CREATE UNIQUE INDEX "tasks_uuid_unique" on "tasks"("uuid");
CREATE TABLE IF NOT EXISTS "recurring_tasks"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "user_id" integer not null,
  "category_id" integer,
  "title" varchar not null,
  "description" text,
  "frequency" varchar not null,
  "frequency_config" text,
  "start_date" date,
  "end_date" date,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("user_id") references "users"("id"),
  foreign key("category_id") references "categories"("id")
);
CREATE INDEX "recurring_tasks_user_id_index" on "recurring_tasks"("user_id");
CREATE INDEX "recurring_tasks_category_id_index" on "recurring_tasks"(
  "category_id"
);
CREATE UNIQUE INDEX "recurring_tasks_uuid_unique" on "recurring_tasks"("uuid");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2026_04_14_165353_create_tasks_table',2);
INSERT INTO migrations VALUES(5,'2026_04_14_165412_create_categories_table',2);
INSERT INTO migrations VALUES(6,'2026_04_30_085024_add_uuids_to_tables',3);
INSERT INTO migrations VALUES(10,'2026_05_09_113400_create_recurring_tasks_table',4);
