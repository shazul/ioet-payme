ALTER DATABASE payroll OWNER TO ioet_test;
GRANT ALL ON SCHEMA public TO ioet_test;
GRANT ALL PRIVILEGES ON DATABASE payroll TO ioet_test;

\c payroll;

CREATE TYPE weekday AS ENUM ('MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU');

CREATE TABLE hourly_rates (
  id SERIAL PRIMARY KEY,
  weekdays weekday[] NOT NULL,
  start_time TIME(0) NOT NULL,
  end_time TIME(0) NOT NULL,
  rate FLOAT NOT NULL
);

INSERT INTO hourly_rates (weekdays, start_time, end_time, rate) VALUES ('{MO,TU,WE,TH,FR}', '00:00', '09:00', 25);
INSERT INTO hourly_rates (weekdays, start_time, end_time, rate) VALUES ('{MO,TU,WE,TH,FR}', '09:00', '18:00', 15);
INSERT INTO hourly_rates (weekdays, start_time, end_time, rate) VALUES ('{MO,TU,WE,TH,FR}', '18:00', '24:00', 20);
INSERT INTO hourly_rates (weekdays, start_time, end_time, rate) VALUES ('{SA,SU}', '00:00', '09:00', 30);
INSERT INTO hourly_rates (weekdays, start_time, end_time, rate) VALUES ('{SA,SU}', '09:00', '18:00', 20);
INSERT INTO hourly_rates (weekdays, start_time, end_time, rate) VALUES ('{SA,SU}', '18:00', '24:00', 25);