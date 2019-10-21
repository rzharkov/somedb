--
-- PostgreSQL database dump
--

-- Dumped from database version 10.3
-- Dumped by pg_dump version 11.0

-- Started on 2019-09-15 17:57:49

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 2874 (class 0 OID 329205)
-- Dependencies: 221
-- Data for Name: measurement_intervals; Type: TABLE DATA; Schema: public; Owner: dbowner
--

INSERT INTO public.measurement_intervals (id, name, status) VALUES (1, 'Day', 1);
INSERT INTO public.measurement_intervals (id, name, status) VALUES (2, 'Hour', 1);


--
-- TOC entry 2872 (class 0 OID 328761)
-- Dependencies: 198
-- Data for Name: station_types; Type: TABLE DATA; Schema: public; Owner: dbowner
--

INSERT INTO public.station_types (id, name, crtime, status, data_format, measurements_table_name) VALUES (9, 'Лизиметрическая', '2018-11-01 05:54:26.784605+00', 1, '[{"DateTime": {"unit": "", "column_name": "measurement_time", "description": "Дата и время измерения ( как есть из выгрузки со станции ). В выгрузке в двух полях, поэтому здесь будет костыль"}}, {"Tens30.1": {"unit": "kPa", "column_name": "pf_30_1", "description": "осмотическое давление почвенной влаги на глубине 30см в первом монолите (среднее значение) "}}, {"Tens30.1min": {"unit": "kPa", "column_name": "pf_30_1_min", "description": "осмотическое давление почвенной влаги на глубине 30см в первом монолите (минимальное значение за интервал измерений)"}}, {"Tens30.1max": {"unit": "kPa", "column_name": "pf_30_1_max", "description": "осмотическое давление почвенной влаги на глубине 30см в первом монолите (максимальное значение за интервал измерений)"}}, {"Tens30.2": {"unit": "kPa", "column_name": "pf_30_2", "description": "осмотическое давление почвенной влаги на глубине 30см в втором монолите (среднее значение)"}}, {"Tens30.2min": {"unit": "kPa", "column_name": "pf_30_2_min", "description": "осмотическое давление почвенной влаги на глубине 30см в втором монолите (минимальное значение за интервал измерений)"}}, {"Tens30.2max": {"unit": "kPa", "column_name": "pf_30_2_max", "description": "осмотическое давление почвенной влаги на глубине 30см в втором монолите (максимальное значение за интервал измерений)"}}, {"Vacuum30": {"unit": "kPa", "column_name": "vac_30", "description": "понижение давления в системе отбора проб воды с глубины 30см (среднее значение)"}}, {"Vacuum30min": {"unit": "kPa", "column_name": "vac_30_min", "description": "понижение давления в системе отбора проб воды с глубины 30см (минимальное значение за интервал измерений)"}}, {"Vacuum30max": {"unit": "kPa", "column_name": "vac_30_max", "description": "понижение давления в системе отбора проб воды с глубины 30см (максимальное значение за интервал измерений)"}}, {"Tens50.1": {"unit": "kPa", "column_name": "pf_50_1", "description": "осмотическое давление почвенной влаги на глубине 50см в первом монолите (среднее значение)"}}, {"Tens50.1min": {"unit": "kPa", "column_name": "pf_50_1_min", "description": "осмотическое давление почвенной влаги на глубине 50см в первом монолите (минимальное значение за интервал измерений)"}}, {"Tens50.1max": {"unit": "kPa", "column_name": "pf_50_1_max", "description": "осмотическое давление почвенной влаги на глубине 50см в первом монолите (максимальное значение за интервал измерений)"}}, {"Tens50.2": {"unit": "kPa", "column_name": "pf_50_2", "description": "осмотическое давление почвенной влаги на глубине 50см в втором монолите (среднее значение)"}}, {"Tens50.2min": {"unit": "kPa", "column_name": "pf_50_2_min", "description": "осмотическое давление почвенной влаги на глубине 50см в втором монолите (минимальное значение за интервал измерений)"}}, {"Tens50.2max": {"unit": "kPa", "column_name": "pf_50_2_max", "description": "осмотическое давление почвенной влаги на глубине 50см в втором монолите (максимальное значение за интервал измерений)"}}, {"Vacuum50": {"unit": "kPa", "column_name": "vac_50", "description": "понижение давления в системе отбора проб воды с глубины 50см (среднее значение)"}}, {"Vacuum50min": {"unit": "kPa", "column_name": "vac_50_min", "description": "понижение давления в системе отбора проб воды с глубины 50см (минимальное значение)"}}, {"Vacuum50max": {"unit": "kPa", "column_name": "vac_50_max", "description": "понижение давления в системе отбора проб воды с глубины 50см (максимальное значение)"}}, {"Tens120.1": {"unit": "kPa", "column_name": "pf_120_1", "description": "осмотическое давление почвенной влаги на глубине 120см в первом монолите (среднее значение)"}}, {"Tens120.1min": {"unit": "kPa", "column_name": "pf_120_1_min", "description": "осмотическое давление почвенной влаги на глубине 120см в первом монолите (минимальное значение за интервал измерений)"}}, {"Tens120.1max": {"unit": "kPa", "column_name": "pf_120_1_max", "description": "осмотическое давление почвенной влаги на глубине 120см в первом монолите (максимальное значение за интервал измерений)"}}, {"Tens120.2": {"unit": "kPa", "column_name": "pf_120_2", "description": "осмотическое давление почвенной влаги на глубине 120см во втором монолите (среднее значение)"}}, {"Tens120.2min": {"unit": "kPa", "column_name": "pf_120_2_min", "description": "осмотическое давление почвенной влаги на глубине 120см в втором монолите (минимальное значение за интервал измерений)"}}, {"Tens120.2max": {"unit": "kPa", "column_name": "pf_120_2_max", "description": "осмотическое давление почвенной влаги на глубине 120см в втором монолите (максимальное значение за интервал измерений)"}}, {"Vacuum120": {"unit": "kPa", "column_name": "vac_120", "description": "понижение давления в системе отбора проб воды с глубины 120см (среднее значение)"}}, {"Vacuum120min": {"unit": "kPa", "column_name": "vac_120_min", "description": "понижение давления в системе отбора проб воды с глубины 120см (минимальное значение)"}}, {"Vacuum120max": {"unit": "kPa", "column_name": "vac_120_max", "description": "понижение давления в системе отбора проб воды с глубины 120см (максимальное значение)"}}, {"UMP30.1": {"unit": "%", "column_name": "moisture_30_1", "description": "влажность почвы на глубине 30см в первом монолите"}}, {"UMP30.2": {"unit": "%", "column_name": "moisture_30_2", "description": "влажность почвы на глубине 30см во втором монолите"}}, {"UMP50.1": {"unit": "%", "column_name": "moisture_50_1", "description": "влажность почвы на глубине 50см в первом монолите"}}, {"UMP50.2": {"unit": "%", "column_name": "moisture_50_2", "description": "влажность почвы на глубине 50см во втором монолите"}}, {"UMP120.1": {"unit": "%", "column_name": "moisture_120_1", "description": "влажность почвы на глубине 120см в первом монолите"}}, {"UMP120.2": {"unit": "%", "column_name": "moisture_120_2", "description": "влажность почвы на глубине 120см во втором монолите"}}, {"EC30.1": {"unit": "mS/cm", "column_name": "e_conductivity_30_1", "description": "электропроводность почвы на глубине 30см в первом монолите"}}, {"EC30.2": {"unit": "mS/cm", "column_name": "e_conductivity_30_2", "description": "электропроводность почвы на глубине 30см во втором монолите"}}, {"EC50.1": {"unit": "mS/cm", "column_name": "e_conductivity_50_1", "description": "электропроводность почвы на глубине 50см в первом монолите"}}, {"EC50.2": {"unit": "mS/cm", "column_name": "e_conductivity_50_2", "description": "электропроводность почвы на глубине 50см во втором монолите"}}, {"EC120.1": {"unit": "mS/cm", "column_name": "e_conductivity_120_1", "description": "электропроводность почвы на глубине 120см в первом монолите"}}, {"EC120.2": {"unit": "mS/cm", "column_name": "e_conductivity_120_2", "description": "электропроводность почвы на глубине 120см во втором монолите"}}, {"Temp30.1": {"unit": "В°C", "column_name": "t_30_1", "description": "температура почвы на глубине 30см в первом монолите"}}, {"Temp30.2": {"unit": "В°C", "column_name": "t_30_2", "description": "температура почвы на глубине 30см во втором монолите"}}, {"Temp50.1": {"unit": "В°C", "column_name": "t_50_1", "description": "температура почвы на глубине 50см в первом монолите"}}, {"Temp50.2": {"unit": "В°C", "column_name": "t_50_2", "description": "температура почвы на глубине 50см во втором монолите"}}, {"Temp120.1": {"unit": "В°C", "column_name": "t_120_1", "description": "температура почвы на глубине 120см в первом монолите"}}, {"Temp120.2": {"unit": "В°C", "column_name": "t_120_2", "description": "температура почвы на глубине 120см во втором монолите"}}, {"Weight 1": {"unit": "kg", "column_name": "weight_1", "description": "масса первого монолита"}}, {"Weight 2": {"unit": "kg", "column_name": "weight_2", "description": "масса второго монолита"}}, {"Drain1": {"unit": "l", "column_name": "drain_1", "description": "объём стока воды через дренаж первого монолита (среднее значение)"}}, {"Drain1min": {"unit": "l", "column_name": "drain_1_min", "description": "объём стока воды через дренаж первого монолита (минимальное значение)"}}, {"Drain1max": {"unit": "l", "column_name": "drain_1_max", "description": "объём стока воды через дренаж первого монолита (максимальное значение)"}}, {"Drain2": {"unit": "l", "column_name": "drain_2", "description": "объём стока воды через дренаж второго монолита (среднее значение)"}}, {"Drain2min": {"unit": "l", "column_name": "drain_2_min", "description": "объём стока воды через дренаж второго монолита (минимальное значение)"}}, {"Drain2max": {"unit": "l", "column_name": "drain_2_max", "description": "объём стока воды через дренаж второго монолита (максимальное значение)"}}, {"Accu": {"unit": "V", "column_name": "accu", "description": "напряжение аккумулятора резервного питания (среднее значение)"}}, {"Accumin": {"unit": "V", "column_name": "accu_min", "description": "напряжение аккумулятора резервного питания (минимальное значение)"}}, {"Accumax": {"unit": "V", "column_name": "accu_max", "description": "напряжение аккумулятора резервного питания (максимальное значение)"}}]', 'public.lysimetric_station_measurements');
INSERT INTO public.station_types (id, name, crtime, status, data_format, measurements_table_name) VALUES (8, 'Гидрометеорологическая', '2018-11-01 05:54:04.037738+00', 1, NULL, 'public.hydrometeorological_station_measurements');


--
-- TOC entry 2880 (class 0 OID 0)
-- Dependencies: 220
-- Name: measurement_intervals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dbowner
--

SELECT pg_catalog.setval('public.measurement_intervals_id_seq', 2, true);


--
-- TOC entry 2881 (class 0 OID 0)
-- Dependencies: 197
-- Name: station_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dbowner
--

SELECT pg_catalog.setval('public.station_types_id_seq', 9, true);


-- Completed on 2019-09-15 17:57:51

--
-- PostgreSQL database dump complete
--

