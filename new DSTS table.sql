CREATE TABLE IF NOT EXISTS dsts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dst_start DATETIME NOT NULL,
    dst_end DATETIME NOT NULL,
    morning_schedule_time INT NOT NULL,
    afternoon_schedule_time INT NOT NULL
);

INSERT INTO dsts (dst_start, dst_end, morning_schedule_time, afternoon_schedule_time)
VALUES ('2024-03-10 02:00:00', '2024-11-03 02:00:00', 90, 60);