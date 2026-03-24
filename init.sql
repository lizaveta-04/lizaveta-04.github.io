-- Создание таблицы feedback на основе вашего ajax.php
CREATE TABLE IF NOT EXISTS feedback (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Предоставление прав
GRANT ALL PRIVILEGES ON TABLE feedback TO postgres;
GRANT USAGE, SELECT ON SEQUENCE feedback_id_seq TO postgres;