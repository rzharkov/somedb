--
-- PostgreSQL database dump
--

-- Dumped from database version 10.3
-- Dumped by pg_dump version 11.0

-- Started on 2019-09-15 15:53:42

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
-- TOC entry 2961 (class 1262 OID 328747)
-- Name: somedb; Type: DATABASE; Schema: -; Owner: somedbowner
--

CREATE ROLE somedbowner;
CREATE USER somedb_app_user PASSWORD 'change_somedb_app_user_password';
CREATE USER somedb_app_admin PASSWORD 'change_somedb_app_admin_password';
GRANT somedb_app_user TO somedb_app_admin;

CREATE DATABASE somedb WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'ru_RU.utf8' LC_CTYPE = 'ru_RU.utf8';

ALTER DATABASE somedb OWNER TO somedbowner;

\connect somedb

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
-- TOC entry 2962 (class 0 OID 0)
-- Dependencies: 2961
-- Name: DATABASE somedb; Type: COMMENT; Schema: -; Owner: somedbowner
--

COMMENT ON DATABASE somedb IS 'Soil and hydrometeorological measurements database.';


--
-- TOC entry 8 (class 2615 OID 328899)
-- Name: utils; Type: SCHEMA; Schema: -; Owner: somedbowner
--

CREATE SCHEMA utils;


ALTER SCHEMA utils OWNER TO somedbowner;

--
-- TOC entry 223 (class 1255 OID 328900)
-- Name: chtime_set_trg_function(); Type: FUNCTION; Schema: utils; Owner: somedbowner
--

CREATE FUNCTION utils.chtime_set_trg_function() RETURNS trigger
    LANGUAGE plpgsql
    AS $$

DECLARE
	v_id_user bigint;
BEGIN
	IF TG_OP in ( 'INSERT', 'UPDATE' ) THEN
		v_id_user = utils.get_id_user();
		IF ( NOT( session_user::text = 'postgres' and v_id_user = -10 ) ) THEN
			NEW.chtime := NOW();
		END IF;
		RETURN NEW;
	ELSE
		RETURN OLD;
	END IF;
END;

$$;


ALTER FUNCTION utils.chtime_set_trg_function() OWNER TO somedbowner;

--
-- TOC entry 228 (class 1255 OID 329051)
-- Name: get_id_user(); Type: FUNCTION; Schema: utils; Owner: somedbowner
--

CREATE FUNCTION utils.get_id_user() RETURNS bigint
    LANGUAGE plpgsql
    AS $$

DECLARE
res bigint;
BEGIN
	res = 0;
	BEGIN
		res = current_setting( 'usrvar.ID_USER' );
	EXCEPTION
	WHEN OTHERS THEN
		CASE ( session_user )
			WHEN 'somedb_app_user' THEN res = 2;
			WHEN 'somedb_app_admin' THEN res = 3;
			WHEN 'postgres' THEN res = -1;
			ELSE raise exception 'id_user not found for current session user' USING ERRCODE = 'insufficient_privilege';
		END CASE;
	END;
	RETURN res;
END;

$$;


ALTER FUNCTION utils.get_id_user() OWNER TO somedbowner;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 211 (class 1259 OID 328869)
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id bigint NOT NULL,
    created_at integer DEFAULT date_part('epoch'::text, now()),
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    id bigint NOT NULL,
    chtime timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.auth_assignment OWNER TO somedbowner;

--
-- TOC entry 214 (class 1259 OID 328908)
-- Name: auth_assignment_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.auth_assignment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.auth_assignment_id_seq OWNER TO somedbowner;

--
-- TOC entry 2966 (class 0 OID 0)
-- Dependencies: 214
-- Name: auth_assignment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.auth_assignment_id_seq OWNED BY public.auth_assignment.id;


--
-- TOC entry 209 (class 1259 OID 328840)
-- Name: auth_item; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.auth_item (
    name character varying(64) NOT NULL,
    type smallint NOT NULL,
    description text,
    rule_name character varying(64),
    data bytea,
    created_at integer,
    updated_at integer,
    id integer NOT NULL,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    chtime timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.auth_item OWNER TO somedbowner;

--
-- TOC entry 210 (class 1259 OID 328854)
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL,
    id integer NOT NULL,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    chtime timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.auth_item_child OWNER TO somedbowner;

--
-- TOC entry 216 (class 1259 OID 328938)
-- Name: auth_item_child_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.auth_item_child_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.auth_item_child_id_seq OWNER TO somedbowner;

--
-- TOC entry 2970 (class 0 OID 0)
-- Dependencies: 216
-- Name: auth_item_child_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.auth_item_child_id_seq OWNED BY public.auth_item_child.id;


--
-- TOC entry 215 (class 1259 OID 328927)
-- Name: auth_item_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.auth_item_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.auth_item_id_seq OWNER TO somedbowner;

--
-- TOC entry 2972 (class 0 OID 0)
-- Dependencies: 215
-- Name: auth_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.auth_item_id_seq OWNED BY public.auth_item.id;


--
-- TOC entry 208 (class 1259 OID 328832)
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.auth_rule (
    name character varying(64) NOT NULL,
    data bytea,
    created_at integer DEFAULT date_part('epoch'::text, now()),
    updated_at integer,
    id integer NOT NULL,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    chtime timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.auth_rule OWNER TO somedbowner;

--
-- TOC entry 217 (class 1259 OID 328945)
-- Name: auth_rule_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.auth_rule_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.auth_rule_id_seq OWNER TO somedbowner;

--
-- TOC entry 2975 (class 0 OID 0)
-- Dependencies: 217
-- Name: auth_rule_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.auth_rule_id_seq OWNED BY public.auth_rule.id;


--
-- TOC entry 206 (class 1259 OID 328816)
-- Name: hydrometeorological_station_measurements; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.hydrometeorological_station_measurements (
    id bigint NOT NULL,
    id_station bigint NOT NULL,
    measurement_time timestamp with time zone NOT NULL,
    helmann numeric,
    wind_direction numeric,
    wind_speed numeric,
    air_temperature numeric,
    air_humidity numeric,
    pressure numeric,
    vaisala numeric,
    radiation numeric,
    accu numeric,
    accu_temperature numeric,
    memory_load numeric,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    id_uploading integer NOT NULL,
    id_measurement_interval integer NOT NULL
);


ALTER TABLE public.hydrometeorological_station_measurements OWNER TO somedbowner;

--
-- TOC entry 2977 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.helmann; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.helmann IS 'количество атмосферных осадков в капельном виде';


--
-- TOC entry 2978 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.wind_direction; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.wind_direction IS 'направление ветра';


--
-- TOC entry 2979 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.wind_speed; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.wind_speed IS 'скорость ветра';


--
-- TOC entry 2980 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.air_temperature; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.air_temperature IS 'температура воздуха';


--
-- TOC entry 2981 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.air_humidity; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.air_humidity IS 'относительная влажность воздуха';


--
-- TOC entry 2982 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.pressure; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.pressure IS 'атмосферное давление';


--
-- TOC entry 2983 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.vaisala; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.vaisala IS 'количество атмосферных осадков в капельном виде';


--
-- TOC entry 2984 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.radiation; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.radiation IS 'интенсивность солнечной радиации';


--
-- TOC entry 2985 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.accu; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.accu IS 'напряжение батареи питания';


--
-- TOC entry 2986 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.accu_temperature; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.accu_temperature IS 'температура батареи питания';


--
-- TOC entry 2987 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.memory_load; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.memory_load IS 'доля свободной памяти логгера';


--
-- TOC entry 2988 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.id_uploading; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.id_uploading IS 'Идентификатор загрузки';


--
-- TOC entry 2989 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN hydrometeorological_station_measurements.id_measurement_interval; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.hydrometeorological_station_measurements.id_measurement_interval IS 'Идентификатор интервала измерений';


--
-- TOC entry 205 (class 1259 OID 328814)
-- Name: hydrometeorological_station_measurements_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.hydrometeorological_station_measurements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.hydrometeorological_station_measurements_id_seq OWNER TO somedbowner;

--
-- TOC entry 2991 (class 0 OID 0)
-- Dependencies: 205
-- Name: hydrometeorological_station_measurements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.hydrometeorological_station_measurements_id_seq OWNED BY public.hydrometeorological_station_measurements.id;


--
-- TOC entry 202 (class 1259 OID 328792)
-- Name: lysimetric_station_measurements; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.lysimetric_station_measurements (
    id bigint NOT NULL,
    id_station bigint NOT NULL,
    measurement_time timestamp with time zone NOT NULL,
    pf_30_1 numeric,
    pf_30_1_min numeric,
    pf_30_1_max numeric,
    pf_30_2 numeric,
    pf_30_2_min numeric,
    pf_30_2_max numeric,
    vac_30 numeric,
    vac_30_min numeric,
    vac_30_max numeric,
    pf_50_1 numeric,
    pf_50_1_min numeric,
    pf_50_1_max numeric,
    pf_50_2 numeric,
    pf_50_2_min numeric,
    pf_50_2_max numeric,
    vac_50 numeric,
    vac_50_min numeric,
    vac_50_max numeric,
    pf_120_1 numeric,
    pf_120_1_min numeric,
    pf_120_1_max numeric,
    pf_120_2 numeric,
    pf_120_2_min numeric,
    pf_120_2_max numeric,
    vac_120 numeric,
    vac_120_min numeric,
    vac_120_max numeric,
    moisture_30_1 numeric,
    moisture_30_2 numeric,
    moisture_50_1 numeric,
    moisture_50_2 numeric,
    moisture_120_1 numeric,
    moisture_120_2 numeric,
    e_conductivity_30_1 numeric,
    e_conductivity_30_2 numeric,
    e_conductivity_50_1 numeric,
    e_conductivity_50_2 numeric,
    e_conductivity_120_1 numeric,
    e_conductivity_120_2 numeric,
    t_30_1 numeric,
    t_30_2 numeric,
    t_50_1 numeric,
    t_50_2 numeric,
    t_120_1 numeric,
    t_120_2 numeric,
    weight_1 numeric,
    weight_2 numeric,
    drain_1 numeric,
    drain_1_min numeric,
    drain_1_max numeric,
    drain_2 numeric,
    drain_2_min numeric,
    drain_2_max numeric,
    accu numeric,
    accu_min numeric,
    accu_max numeric,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    id_uploading integer NOT NULL,
    id_measurement_interval integer NOT NULL
);


ALTER TABLE public.lysimetric_station_measurements OWNER TO somedbowner;

--
-- TOC entry 2993 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.measurement_time; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.measurement_time IS 'Дата и время измерения ( как есть из выгрузки со станции )';


--
-- TOC entry 2994 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_30_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_30_1 IS 'осмотическое давление почвенной влаги на глубине 30см в первом монолите (среднее значение) ';


--
-- TOC entry 2995 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_30_1_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_30_1_min IS 'осмотическое давление почвенной влаги на глубине 30см в первом монолите (минимальное значение за интервал измерений)';


--
-- TOC entry 2996 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_30_1_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_30_1_max IS 'осмотическое давление почвенной влаги на глубине 30см в первом монолите (максимальное значение за интервал измерений)';


--
-- TOC entry 2997 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_30_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_30_2 IS 'осмотическое давление почвенной влаги на глубине 30см в втором монолите (среднее значение)';


--
-- TOC entry 2998 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_30_2_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_30_2_min IS 'осмотическое давление почвенной влаги на глубине 30см в втором монолите (минимальное значение за интервал измерений)';


--
-- TOC entry 2999 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_30_2_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_30_2_max IS 'осмотическое давление почвенной влаги на глубине 30см в втором монолите (максимальное значение за интервал измерений)';


--
-- TOC entry 3000 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_30; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_30 IS 'понижение давления в системе отбора проб воды с глубины 30см (среднее значение)';


--
-- TOC entry 3001 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_30_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_30_min IS 'понижение давления в системе отбора проб воды с глубины 30см (минимальное значение за интервал измерений)';


--
-- TOC entry 3002 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_30_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_30_max IS 'понижение давления в системе отбора проб воды с глубины 30см (максимальное значение за интервал измерений)';


--
-- TOC entry 3003 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_50_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_50_1 IS 'осмотическое давление почвенной влаги на глубине 50см в первом монолите (среднее значение)';


--
-- TOC entry 3004 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_50_1_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_50_1_min IS 'осмотическое давление почвенной влаги на глубине 50см в первом монолите (минимальное значение за интервал измерений)';


--
-- TOC entry 3005 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_50_1_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_50_1_max IS 'осмотическое давление почвенной влаги на глубине 50см в первом монолите (максимальное значение за интервал измерений)';


--
-- TOC entry 3006 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_50_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_50_2 IS 'осмотическое давление почвенной влаги на глубине 50см в втором монолите (среднее значение)';


--
-- TOC entry 3007 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_50_2_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_50_2_min IS 'осмотическое давление почвенной влаги на глубине 50см в втором монолите (минимальное значение за интервал измерений)';


--
-- TOC entry 3008 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_50_2_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_50_2_max IS 'осмотическое давление почвенной влаги на глубине 50см в втором монолите (максимальное значение за интервал измерений)';


--
-- TOC entry 3009 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_50; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_50 IS 'понижение давления в системе отбора проб воды с глубины 50см (среднее значение)';


--
-- TOC entry 3010 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_50_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_50_min IS 'понижение давления в системе отбора проб воды с глубины 50см (минимальное значение)';


--
-- TOC entry 3011 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_50_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_50_max IS 'понижение давления в системе отбора проб воды с глубины 50см (максимальное значение)';


--
-- TOC entry 3012 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_120_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_120_1 IS 'осмотическое давление почвенной влаги на глубине 120см в первом монолите (среднее значение)';


--
-- TOC entry 3013 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_120_1_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_120_1_min IS 'осмотическое давление почвенной влаги на глубине 120см в первом монолите (минимальное значение за интервал измерений)';


--
-- TOC entry 3014 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_120_1_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_120_1_max IS 'осмотическое давление почвенной влаги на глубине 120см в первом монолите (максимальное значение за интервал измерений)';


--
-- TOC entry 3015 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_120_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_120_2 IS 'осмотическое давление почвенной влаги на глубине 120см во втором монолите (среднее значение)';


--
-- TOC entry 3016 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_120_2_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_120_2_min IS 'осмотическое давление почвенной влаги на глубине 120см в втором монолите (минимальное значение за интервал измерений)';


--
-- TOC entry 3017 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.pf_120_2_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.pf_120_2_max IS 'осмотическое давление почвенной влаги на глубине 120см в втором монолите (максимальное значение за интервал измерений)';


--
-- TOC entry 3018 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_120; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_120 IS 'понижение давления в системе отбора проб воды с глубины 120см (среднее значение)';


--
-- TOC entry 3019 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_120_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_120_min IS 'понижение давления в системе отбора проб воды с глубины 120см (минимальное значение)';


--
-- TOC entry 3020 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.vac_120_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.vac_120_max IS 'понижение давления в системе отбора проб воды с глубины 120см (максимальное значение)';


--
-- TOC entry 3021 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.moisture_30_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.moisture_30_1 IS 'влажность почвы на глубине 30см в первом монолите';


--
-- TOC entry 3022 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.moisture_30_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.moisture_30_2 IS 'влажность почвы на глубине 30см во втором монолите';


--
-- TOC entry 3023 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.moisture_50_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.moisture_50_1 IS 'влажность почвы на глубине 50см в первом монолите';


--
-- TOC entry 3024 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.moisture_50_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.moisture_50_2 IS 'влажность почвы на глубине 50см во втором монолите';


--
-- TOC entry 3025 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.moisture_120_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.moisture_120_1 IS 'влажность почвы на глубине 120см в первом монолите';


--
-- TOC entry 3026 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.moisture_120_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.moisture_120_2 IS 'влажность почвы на глубине 120см во втором монолите';


--
-- TOC entry 3027 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.e_conductivity_30_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.e_conductivity_30_1 IS 'электропроводность почвы на глубине 30см в первом монолите';


--
-- TOC entry 3028 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.e_conductivity_30_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.e_conductivity_30_2 IS 'электропроводность почвы на глубине 30см во втором монолите';


--
-- TOC entry 3029 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.e_conductivity_50_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.e_conductivity_50_1 IS 'электропроводность почвы на глубине 50см в первом монолите';


--
-- TOC entry 3030 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.e_conductivity_50_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.e_conductivity_50_2 IS 'электропроводность почвы на глубине 50см во втором монолите';


--
-- TOC entry 3031 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.e_conductivity_120_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.e_conductivity_120_1 IS 'электропроводность почвы на глубине 120см в первом монолите';


--
-- TOC entry 3032 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.e_conductivity_120_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.e_conductivity_120_2 IS 'электропроводность почвы на глубине 120см во втором монолите';


--
-- TOC entry 3033 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.t_30_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.t_30_1 IS 'температура почвы на глубине 30см в первом монолите';


--
-- TOC entry 3034 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.t_30_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.t_30_2 IS 'температура почвы на глубине 30см во втором монолите';


--
-- TOC entry 3035 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.t_50_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.t_50_1 IS 'температура почвы на глубине 50см в первом монолите';


--
-- TOC entry 3036 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.t_50_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.t_50_2 IS 'температура почвы на глубине 50см во втором монолите';


--
-- TOC entry 3037 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.t_120_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.t_120_1 IS 'температура почвы на глубине 120см в первом монолите';


--
-- TOC entry 3038 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.t_120_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.t_120_2 IS 'температура почвы на глубине 120см во втором монолите';


--
-- TOC entry 3039 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.weight_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.weight_1 IS 'масса первого монолита';


--
-- TOC entry 3040 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.weight_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.weight_2 IS 'масса второго монолита';


--
-- TOC entry 3041 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.drain_1; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.drain_1 IS 'объём стока воды через дренаж первого монолита (среднее значение)';


--
-- TOC entry 3042 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.drain_1_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.drain_1_min IS 'объём стока воды через дренаж первого монолита (минимальное значение)';


--
-- TOC entry 3043 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.drain_1_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.drain_1_max IS 'объём стока воды через дренаж первого монолита (максимальное значение)';


--
-- TOC entry 3044 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.drain_2; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.drain_2 IS 'объём стока воды через дренаж второго монолита (среднее значение)';


--
-- TOC entry 3045 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.drain_2_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.drain_2_min IS 'объём стока воды через дренаж второго монолита (минимальное значение)';


--
-- TOC entry 3046 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.drain_2_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.drain_2_max IS 'объём стока воды через дренаж второго монолита (максимальное значение)';


--
-- TOC entry 3047 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.accu; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.accu IS 'напряжение аккумулятора резервного питания (среднее значение)';


--
-- TOC entry 3048 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.accu_min; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.accu_min IS 'напряжение аккумулятора резервного питания (минимальное значение)';


--
-- TOC entry 3049 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.accu_max; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.accu_max IS 'напряжение аккумулятора резервного питания (максимальное значение)';


--
-- TOC entry 3050 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.id_uploading; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.id_uploading IS 'Идентификатор загрузки';


--
-- TOC entry 3051 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN lysimetric_station_measurements.id_measurement_interval; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.lysimetric_station_measurements.id_measurement_interval IS 'Идентификатор интервала измерений';


--
-- TOC entry 201 (class 1259 OID 328790)
-- Name: lysimetric_station_measurements_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.lysimetric_station_measurements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lysimetric_station_measurements_id_seq OWNER TO somedbowner;

--
-- TOC entry 3053 (class 0 OID 0)
-- Dependencies: 201
-- Name: lysimetric_station_measurements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.lysimetric_station_measurements_id_seq OWNED BY public.lysimetric_station_measurements.id;


--
-- TOC entry 221 (class 1259 OID 329205)
-- Name: measurement_intervals; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.measurement_intervals (
    id bigint NOT NULL,
    name character varying NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    CONSTRAINT measurement_intervals_name_check CHECK ((length(btrim((name)::text)) > 0))
);


ALTER TABLE public.measurement_intervals OWNER TO somedbowner;

--
-- TOC entry 3055 (class 0 OID 0)
-- Dependencies: 221
-- Name: TABLE measurement_intervals; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON TABLE public.measurement_intervals IS 'Возможные интервалы измерений';


--
-- TOC entry 220 (class 1259 OID 329203)
-- Name: measurement_intervals_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.measurement_intervals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.measurement_intervals_id_seq OWNER TO somedbowner;

--
-- TOC entry 3057 (class 0 OID 0)
-- Dependencies: 220
-- Name: measurement_intervals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.measurement_intervals_id_seq OWNED BY public.measurement_intervals.id;


--
-- TOC entry 207 (class 1259 OID 328827)
-- Name: migration; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


ALTER TABLE public.migration OWNER TO somedbowner;

--
-- TOC entry 204 (class 1259 OID 328804)
-- Name: soil_moisture_station_measurements; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.soil_moisture_station_measurements (
    id bigint NOT NULL,
    id_station bigint NOT NULL,
    measurement_time timestamp with time zone NOT NULL,
    t_30 numeric,
    moisture_30 numeric,
    e_conductivity_30 numeric,
    t_2_30 numeric,
    pf_30 numeric,
    t_60 numeric,
    moisure_60 numeric,
    e_conductivity_60 numeric,
    t_2_60 numeric,
    pf_60 numeric,
    t_120 numeric,
    moisure_120 numeric,
    e_conductivity_120 numeric,
    t_2_120 numeric,
    pf_120 numeric,
    accu numeric,
    accu_temperature numeric,
    memory_load numeric,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    id_uploading integer NOT NULL,
    id_measurement_interval integer NOT NULL
);


ALTER TABLE public.soil_moisture_station_measurements OWNER TO somedbowner;

--
-- TOC entry 3060 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.t_30; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.t_30 IS 'температура на глубине 30 см.';


--
-- TOC entry 3061 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.moisture_30; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.moisture_30 IS 'объемная влажность почвы на глубине 30 см.';


--
-- TOC entry 3062 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.e_conductivity_30; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.e_conductivity_30 IS 'проводимость на глубине 30 см.';


--
-- TOC entry 3063 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.t_2_30; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.t_2_30 IS 'температура на глубине 30 см.';


--
-- TOC entry 3064 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.pf_30; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.pf_30 IS 'потенциал почвенной влаги на глубине 30 см.';


--
-- TOC entry 3065 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.t_60; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.t_60 IS 'температура на глубине 60 см.';


--
-- TOC entry 3066 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.moisure_60; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.moisure_60 IS 'объемная влажность почвы на глубине 60 см.';


--
-- TOC entry 3067 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.e_conductivity_60; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.e_conductivity_60 IS 'проводимость на глубине 60 см.';


--
-- TOC entry 3068 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.t_2_60; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.t_2_60 IS 'температура на глубине 60 см.';


--
-- TOC entry 3069 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.pf_60; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.pf_60 IS 'потенциал почвенной влаги на глубине 60 см.';


--
-- TOC entry 3070 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.t_120; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.t_120 IS 'температура на глубине 120 см.';


--
-- TOC entry 3071 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.moisure_120; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.moisure_120 IS 'объемная влажность почвы на глубине 120 см.';


--
-- TOC entry 3072 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.e_conductivity_120; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.e_conductivity_120 IS 'проводимость на глубине 120 см.';


--
-- TOC entry 3073 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.t_2_120; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.t_2_120 IS 'температура на глубине 120 см.';


--
-- TOC entry 3074 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.pf_120; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.pf_120 IS 'потенциал почвенной влаги на глубине 120 см.';


--
-- TOC entry 3075 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.accu; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.accu IS 'напряжение аккумуляторной батареи.';


--
-- TOC entry 3076 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.accu_temperature; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.accu_temperature IS 'температура аккумуляторной батареи.';


--
-- TOC entry 3077 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.memory_load; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.memory_load IS 'заполнение памяти логгера.';


--
-- TOC entry 3078 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.id_uploading; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.id_uploading IS 'Идентификатор загрузки';


--
-- TOC entry 3079 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN soil_moisture_station_measurements.id_measurement_interval; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.soil_moisture_station_measurements.id_measurement_interval IS 'Идентификатор интервала измерений';


--
-- TOC entry 203 (class 1259 OID 328802)
-- Name: soil_moisture_station_measurements_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.soil_moisture_station_measurements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.soil_moisture_station_measurements_id_seq OWNER TO somedbowner;

--
-- TOC entry 3081 (class 0 OID 0)
-- Dependencies: 203
-- Name: soil_moisture_station_measurements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.soil_moisture_station_measurements_id_seq OWNED BY public.soil_moisture_station_measurements.id;


--
-- TOC entry 198 (class 1259 OID 328761)
-- Name: station_types; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.station_types (
    id bigint NOT NULL,
    name character varying NOT NULL,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    data_format jsonb,
    measurements_table_name character varying NOT NULL
);


ALTER TABLE public.station_types OWNER TO somedbowner;

--
-- TOC entry 3083 (class 0 OID 0)
-- Dependencies: 198
-- Name: TABLE station_types; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON TABLE public.station_types IS 'List of measurement station types';


--
-- TOC entry 3084 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN station_types.name; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.station_types.name IS 'Название типа станции';


--
-- TOC entry 3085 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN station_types.data_format; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.station_types.data_format IS 'Сопоставления для названий полей в базе и в выдаче самой станции. Единицы измерения.';


--
-- TOC entry 3086 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN station_types.measurements_table_name; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.station_types.measurements_table_name IS 'Таблица с данными измерений';


--
-- TOC entry 197 (class 1259 OID 328759)
-- Name: station_types_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.station_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.station_types_id_seq OWNER TO somedbowner;

--
-- TOC entry 3088 (class 0 OID 0)
-- Dependencies: 197
-- Name: station_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.station_types_id_seq OWNED BY public.station_types.id;


--
-- TOC entry 200 (class 1259 OID 328772)
-- Name: stations; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.stations (
    id bigint NOT NULL,
    name character varying NOT NULL,
    id_type bigint NOT NULL,
    address character varying NOT NULL,
    comment character varying,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    timezone character varying DEFAULT '+7'::character varying NOT NULL
);


ALTER TABLE public.stations OWNER TO somedbowner;

--
-- TOC entry 3090 (class 0 OID 0)
-- Dependencies: 200
-- Name: TABLE stations; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON TABLE public.stations IS 'Measurement stations';


--
-- TOC entry 3091 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN stations.name; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.stations.name IS 'Условное название измерительной станции';


--
-- TOC entry 3092 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN stations.id_type; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.stations.id_type IS 'Тип измерительной станции из списка station_types';


--
-- TOC entry 3093 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN stations.address; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.stations.address IS 'Адрес, координаты.';


--
-- TOC entry 3094 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN stations.comment; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.stations.comment IS 'Комментарии';


--
-- TOC entry 3095 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN stations.timezone; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.stations.timezone IS 'Часовой пояс в котором работают часы станции';


--
-- TOC entry 199 (class 1259 OID 328770)
-- Name: stations_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.stations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.stations_id_seq OWNER TO somedbowner;

--
-- TOC entry 3097 (class 0 OID 0)
-- Dependencies: 199
-- Name: stations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.stations_id_seq OWNED BY public.stations.id;


--
-- TOC entry 219 (class 1259 OID 329151)
-- Name: uploadings; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public.uploadings (
    id bigint NOT NULL,
    name character varying NOT NULL,
    filename character varying NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    comment character varying,
    id_measurement_interval integer NOT NULL,
    id_station integer NOT NULL
);


ALTER TABLE public.uploadings OWNER TO somedbowner;

--
-- TOC entry 3099 (class 0 OID 0)
-- Dependencies: 219
-- Name: TABLE uploadings; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON TABLE public.uploadings IS 'Список всех заливок данных. Чтобы можно было учитывать когда и зачем были залиты те или иные измерения';


--
-- TOC entry 3100 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN uploadings.name; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.uploadings.name IS 'Пока не понятно что, но заполним названием файла';


--
-- TOC entry 3101 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN uploadings.filename; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.uploadings.filename IS 'Название файла из которого заливались данные';


--
-- TOC entry 3102 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN uploadings.id_measurement_interval; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.uploadings.id_measurement_interval IS 'Интервал измерений';


--
-- TOC entry 3103 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN uploadings.id_station; Type: COMMENT; Schema: public; Owner: somedbowner
--

COMMENT ON COLUMN public.uploadings.id_station IS 'Идентификатор измерительной станции';


--
-- TOC entry 218 (class 1259 OID 329149)
-- Name: uploadings_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.uploadings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.uploadings_id_seq OWNER TO somedbowner;

--
-- TOC entry 3105 (class 0 OID 0)
-- Dependencies: 218
-- Name: uploadings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.uploadings_id_seq OWNED BY public.uploadings.id;


--
-- TOC entry 213 (class 1259 OID 328883)
-- Name: user; Type: TABLE; Schema: public; Owner: somedbowner
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    auth_key character varying(32) NOT NULL,
    password_hash character varying(255) NOT NULL,
    password_reset_token character varying(255),
    email character varying(255) NOT NULL,
    status smallint DEFAULT 10 NOT NULL,
    created_at integer DEFAULT date_part('epoch'::text, now()) NOT NULL,
    updated_at integer NOT NULL,
    crtime timestamp with time zone DEFAULT now() NOT NULL,
    chtime timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public."user" OWNER TO somedbowner;

--
-- TOC entry 212 (class 1259 OID 328881)
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: somedbowner
--

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO somedbowner;

--
-- TOC entry 3108 (class 0 OID 0)
-- Dependencies: 212
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: somedbowner
--

ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;


--
-- TOC entry 2778 (class 2604 OID 328910)
-- Name: auth_assignment id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.auth_assignment ALTER COLUMN id SET DEFAULT nextval('public.auth_assignment_id_seq'::regclass);


--
-- TOC entry 2770 (class 2604 OID 328929)
-- Name: auth_item id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.auth_item ALTER COLUMN id SET DEFAULT nextval('public.auth_item_id_seq'::regclass);


--
-- TOC entry 2773 (class 2604 OID 328940)
-- Name: auth_item_child id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.auth_item_child ALTER COLUMN id SET DEFAULT nextval('public.auth_item_child_id_seq'::regclass);


--
-- TOC entry 2766 (class 2604 OID 328947)
-- Name: auth_rule id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.auth_rule ALTER COLUMN id SET DEFAULT nextval('public.auth_rule_id_seq'::regclass);


--
-- TOC entry 2764 (class 2604 OID 328819)
-- Name: hydrometeorological_station_measurements id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.hydrometeorological_station_measurements ALTER COLUMN id SET DEFAULT nextval('public.hydrometeorological_station_measurements_id_seq'::regclass);


--
-- TOC entry 2760 (class 2604 OID 328795)
-- Name: lysimetric_station_measurements id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.lysimetric_station_measurements ALTER COLUMN id SET DEFAULT nextval('public.lysimetric_station_measurements_id_seq'::regclass);


--
-- TOC entry 2788 (class 2604 OID 329208)
-- Name: measurement_intervals id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.measurement_intervals ALTER COLUMN id SET DEFAULT nextval('public.measurement_intervals_id_seq'::regclass);


--
-- TOC entry 2762 (class 2604 OID 328807)
-- Name: soil_moisture_station_measurements id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.soil_moisture_station_measurements ALTER COLUMN id SET DEFAULT nextval('public.soil_moisture_station_measurements_id_seq'::regclass);


--
-- TOC entry 2753 (class 2604 OID 328764)
-- Name: station_types id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.station_types ALTER COLUMN id SET DEFAULT nextval('public.station_types_id_seq'::regclass);


--
-- TOC entry 2756 (class 2604 OID 328775)
-- Name: stations id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.stations ALTER COLUMN id SET DEFAULT nextval('public.stations_id_seq'::regclass);


--
-- TOC entry 2785 (class 2604 OID 329154)
-- Name: uploadings id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.uploadings ALTER COLUMN id SET DEFAULT nextval('public.uploadings_id_seq'::regclass);


--
-- TOC entry 2780 (class 2604 OID 328886)
-- Name: user id; Type: DEFAULT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);


--
-- TOC entry 2815 (class 2606 OID 328917)
-- Name: auth_assignment auth_assignment_pk; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.auth_assignment
    ADD CONSTRAINT auth_assignment_pk PRIMARY KEY (id);


--
-- TOC entry 2812 (class 2606 OID 328958)
-- Name: auth_item_child auth_item_child_pk; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_pk PRIMARY KEY (id);


--
-- TOC entry 2808 (class 2606 OID 328956)
-- Name: auth_item auth_item_pk; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.auth_item
    ADD CONSTRAINT auth_item_pk PRIMARY KEY (id);


--
-- TOC entry 2805 (class 2606 OID 328960)
-- Name: auth_rule auth_rule_pk; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.auth_rule
    ADD CONSTRAINT auth_rule_pk PRIMARY KEY (id);


--
-- TOC entry 2800 (class 2606 OID 328825)
-- Name: hydrometeorological_station_measurements hydrometeorological_station_measurements_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.hydrometeorological_station_measurements
    ADD CONSTRAINT hydrometeorological_station_measurements_pkey PRIMARY KEY (id);


--
-- TOC entry 2796 (class 2606 OID 328801)
-- Name: lysimetric_station_measurements lysimetric_station_measurements_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.lysimetric_station_measurements
    ADD CONSTRAINT lysimetric_station_measurements_pkey PRIMARY KEY (id);


--
-- TOC entry 2828 (class 2606 OID 329215)
-- Name: measurement_intervals measurement_intervals_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.measurement_intervals
    ADD CONSTRAINT measurement_intervals_pkey PRIMARY KEY (id);


--
-- TOC entry 2802 (class 2606 OID 328831)
-- Name: migration migration_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- TOC entry 2798 (class 2606 OID 328813)
-- Name: soil_moisture_station_measurements soil_moisture_station_measurements_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.soil_moisture_station_measurements
    ADD CONSTRAINT soil_moisture_station_measurements_pkey PRIMARY KEY (id);


--
-- TOC entry 2792 (class 2606 OID 328769)
-- Name: station_types station_types_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.station_types
    ADD CONSTRAINT station_types_pkey PRIMARY KEY (id);


--
-- TOC entry 2794 (class 2606 OID 328780)
-- Name: stations stations_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.stations
    ADD CONSTRAINT stations_pkey PRIMARY KEY (id);


--
-- TOC entry 2826 (class 2606 OID 329160)
-- Name: uploadings uploadings_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.uploadings
    ADD CONSTRAINT uploadings_pkey PRIMARY KEY (id);


--
-- TOC entry 2818 (class 2606 OID 328898)
-- Name: user user_email_key; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_email_key UNIQUE (email);


--
-- TOC entry 2820 (class 2606 OID 328896)
-- Name: user user_password_reset_token_key; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_password_reset_token_key UNIQUE (password_reset_token);


--
-- TOC entry 2822 (class 2606 OID 328892)
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- TOC entry 2824 (class 2606 OID 328894)
-- Name: user user_username_key; Type: CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_username_key UNIQUE (username);


--
-- TOC entry 2813 (class 1259 OID 328919)
-- Name: auth_assignment_item_user_unq; Type: INDEX; Schema: public; Owner: somedbowner
--

CREATE UNIQUE INDEX auth_assignment_item_user_unq ON public.auth_assignment USING btree (item_name, user_id);


--
-- TOC entry 2816 (class 1259 OID 328920)
-- Name: auth_assignment_user_id_idx; Type: INDEX; Schema: public; Owner: somedbowner
--

CREATE INDEX auth_assignment_user_id_idx ON public.auth_assignment USING btree (user_id);


--
-- TOC entry 2810 (class 1259 OID 328962)
-- Name: auth_item_child_parent_child_unq; Type: INDEX; Schema: public; Owner: somedbowner
--

CREATE UNIQUE INDEX auth_item_child_parent_child_unq ON public.auth_item_child USING btree (parent, child);


--
-- TOC entry 2806 (class 1259 OID 328961)
-- Name: auth_item_name_unq; Type: INDEX; Schema: public; Owner: somedbowner
--

CREATE UNIQUE INDEX auth_item_name_unq ON public.auth_item USING btree (name);


--
-- TOC entry 2803 (class 1259 OID 328963)
-- Name: auth_rule_name_unq; Type: INDEX; Schema: public; Owner: somedbowner
--

CREATE UNIQUE INDEX auth_rule_name_unq ON public.auth_rule USING btree (name);


--
-- TOC entry 2809 (class 1259 OID 328853)
-- Name: idx-auth_item-type; Type: INDEX; Schema: public; Owner: somedbowner
--

CREATE INDEX "idx-auth_item-type" ON public.auth_item USING btree (type);


--
-- TOC entry 2833 (class 2620 OID 329021)
-- Name: auth_assignment auth_assignment_chtime_trigger; Type: TRIGGER; Schema: public; Owner: somedbowner
--

CREATE TRIGGER auth_assignment_chtime_trigger BEFORE INSERT OR DELETE OR UPDATE ON public.auth_assignment FOR EACH ROW EXECUTE PROCEDURE utils.chtime_set_trg_function();


--
-- TOC entry 2832 (class 2620 OID 329023)
-- Name: auth_item_child auth_item_child_chtime_trigger; Type: TRIGGER; Schema: public; Owner: somedbowner
--

CREATE TRIGGER auth_item_child_chtime_trigger BEFORE INSERT OR DELETE OR UPDATE ON public.auth_item_child FOR EACH ROW EXECUTE PROCEDURE utils.chtime_set_trg_function();


--
-- TOC entry 2831 (class 2620 OID 329022)
-- Name: auth_item auth_item_chtime_trigger; Type: TRIGGER; Schema: public; Owner: somedbowner
--

CREATE TRIGGER auth_item_chtime_trigger BEFORE INSERT OR DELETE OR UPDATE ON public.auth_item FOR EACH ROW EXECUTE PROCEDURE utils.chtime_set_trg_function();


--
-- TOC entry 2830 (class 2620 OID 329024)
-- Name: auth_rule auth_rule_chtime_trigger; Type: TRIGGER; Schema: public; Owner: somedbowner
--

CREATE TRIGGER auth_rule_chtime_trigger BEFORE INSERT OR DELETE OR UPDATE ON public.auth_rule FOR EACH ROW EXECUTE PROCEDURE utils.chtime_set_trg_function();


--
-- TOC entry 2834 (class 2620 OID 329048)
-- Name: user user_chtime_trigger; Type: TRIGGER; Schema: public; Owner: somedbowner
--

CREATE TRIGGER user_chtime_trigger BEFORE INSERT OR DELETE OR UPDATE ON public."user" FOR EACH ROW EXECUTE PROCEDURE utils.chtime_set_trg_function();


--
-- TOC entry 2829 (class 2606 OID 329171)
-- Name: stations station_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: somedbowner
--

ALTER TABLE ONLY public.stations
    ADD CONSTRAINT station_types_fk FOREIGN KEY (id_type) REFERENCES public.station_types(id);


--
-- TOC entry 2963 (class 0 OID 0)
-- Dependencies: 3
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: somedbowner
--

REVOKE ALL ON SCHEMA public FROM "postgres";
REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO somedbowner;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- TOC entry 2964 (class 0 OID 0)
-- Dependencies: 8
-- Name: SCHEMA utils; Type: ACL; Schema: -; Owner: somedbowner
--

GRANT USAGE ON SCHEMA utils TO PUBLIC;


--
-- TOC entry 2965 (class 0 OID 0)
-- Dependencies: 211
-- Name: TABLE auth_assignment; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.auth_assignment TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.auth_assignment TO somedb_app_user;


--
-- TOC entry 2967 (class 0 OID 0)
-- Dependencies: 214
-- Name: SEQUENCE auth_assignment_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.auth_assignment_id_seq TO somedb_app_user;


--
-- TOC entry 2968 (class 0 OID 0)
-- Dependencies: 209
-- Name: TABLE auth_item; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.auth_item TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.auth_item TO somedb_app_user;


--
-- TOC entry 2969 (class 0 OID 0)
-- Dependencies: 210
-- Name: TABLE auth_item_child; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.auth_item_child TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.auth_item_child TO somedb_app_user;


--
-- TOC entry 2971 (class 0 OID 0)
-- Dependencies: 216
-- Name: SEQUENCE auth_item_child_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.auth_item_child_id_seq TO somedb_app_user;


--
-- TOC entry 2973 (class 0 OID 0)
-- Dependencies: 215
-- Name: SEQUENCE auth_item_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.auth_item_id_seq TO somedb_app_user;


--
-- TOC entry 2974 (class 0 OID 0)
-- Dependencies: 208
-- Name: TABLE auth_rule; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.auth_rule TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.auth_rule TO somedb_app_user;


--
-- TOC entry 2976 (class 0 OID 0)
-- Dependencies: 217
-- Name: SEQUENCE auth_rule_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.auth_rule_id_seq TO somedb_app_user;


--
-- TOC entry 2990 (class 0 OID 0)
-- Dependencies: 206
-- Name: TABLE hydrometeorological_station_measurements; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.hydrometeorological_station_measurements TO somedb_app_user;


--
-- TOC entry 2992 (class 0 OID 0)
-- Dependencies: 205
-- Name: SEQUENCE hydrometeorological_station_measurements_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.hydrometeorological_station_measurements_id_seq TO somedb_app_user;


--
-- TOC entry 3052 (class 0 OID 0)
-- Dependencies: 202
-- Name: TABLE lysimetric_station_measurements; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.lysimetric_station_measurements TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.lysimetric_station_measurements TO somedb_app_user;


--
-- TOC entry 3054 (class 0 OID 0)
-- Dependencies: 201
-- Name: SEQUENCE lysimetric_station_measurements_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.lysimetric_station_measurements_id_seq TO somedb_app_user;


--
-- TOC entry 3056 (class 0 OID 0)
-- Dependencies: 221
-- Name: TABLE measurement_intervals; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,UPDATE ON TABLE public.measurement_intervals TO somedb_app_user;


--
-- TOC entry 3058 (class 0 OID 0)
-- Dependencies: 220
-- Name: SEQUENCE measurement_intervals_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.measurement_intervals_id_seq TO somedb_app_user;


--
-- TOC entry 3059 (class 0 OID 0)
-- Dependencies: 207
-- Name: TABLE migration; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.migration TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.migration TO somedb_app_user;


--
-- TOC entry 3080 (class 0 OID 0)
-- Dependencies: 204
-- Name: TABLE soil_moisture_station_measurements; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.soil_moisture_station_measurements TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.soil_moisture_station_measurements TO somedb_app_user;


--
-- TOC entry 3082 (class 0 OID 0)
-- Dependencies: 203
-- Name: SEQUENCE soil_moisture_station_measurements_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.soil_moisture_station_measurements_id_seq TO somedb_app_user;


--
-- TOC entry 3087 (class 0 OID 0)
-- Dependencies: 198
-- Name: TABLE station_types; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.station_types TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.station_types TO somedb_app_user;


--
-- TOC entry 3089 (class 0 OID 0)
-- Dependencies: 197
-- Name: SEQUENCE station_types_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.station_types_id_seq TO somedb_app_user;


--
-- TOC entry 3096 (class 0 OID 0)
-- Dependencies: 200
-- Name: TABLE stations; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.stations TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.stations TO somedb_app_user;


--
-- TOC entry 3098 (class 0 OID 0)
-- Dependencies: 199
-- Name: SEQUENCE stations_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.stations_id_seq TO somedb_app_user;


--
-- TOC entry 3104 (class 0 OID 0)
-- Dependencies: 219
-- Name: TABLE uploadings; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.uploadings TO somedb_app_user;


--
-- TOC entry 3106 (class 0 OID 0)
-- Dependencies: 218
-- Name: SEQUENCE uploadings_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.uploadings_id_seq TO somedb_app_user;


--
-- TOC entry 3107 (class 0 OID 0)
-- Dependencies: 213
-- Name: TABLE "user"; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public."user" TO somedb_app_admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public."user" TO somedb_app_user;


--
-- TOC entry 3109 (class 0 OID 0)
-- Dependencies: 212
-- Name: SEQUENCE user_id_seq; Type: ACL; Schema: public; Owner: somedbowner
--

GRANT SELECT,UPDATE ON SEQUENCE public.user_id_seq TO somedb_app_admin;
GRANT SELECT,UPDATE ON SEQUENCE public.user_id_seq TO somedb_app_user;


-- Completed on 2019-09-15 15:53:42

--
-- PostgreSQL database dump complete
--

