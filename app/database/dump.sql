--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.6
-- Dumped by pg_dump version 9.5.6

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: activities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE activities (
    id integer NOT NULL,
    name text,
    dt_start timestamp without time zone,
    dt_end timestamp without time zone,
    local_name text,
    local_geolocation text,
    event_id integer
);


ALTER TABLE activities OWNER TO postgres;

--
-- Name: activities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE activities_id_seq OWNER TO postgres;

--
-- Name: activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE activities_id_seq OWNED BY activities.id;


--
-- Name: attachments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE attachments (
    id integer NOT NULL,
    name text,
    size text,
    local text,
    activity_id integer,
    type integer
);


ALTER TABLE attachments OWNER TO postgres;

--
-- Name: attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE attachments_id_seq OWNER TO postgres;

--
-- Name: attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE attachments_id_seq OWNED BY attachments.id;


--
-- Name: events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE events (
    id integer NOT NULL,
    name text,
    banner text,
    contact_name text,
    contact_phone text,
    contact_mail text,
    description text,
    dt_start timestamp without time zone,
    dt_end timestamp without time zone
);


ALTER TABLE events OWNER TO postgres;

--
-- Name: events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE events_id_seq OWNER TO postgres;

--
-- Name: events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE events_id_seq OWNED BY events.id;


--
-- Name: inscriptions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE inscriptions (
    id integer NOT NULL,
    email text,
    hash text,
    event_id integer
);


ALTER TABLE inscriptions OWNER TO postgres;

--
-- Name: inscriptions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE inscriptions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inscriptions_id_seq OWNER TO postgres;

--
-- Name: inscriptions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE inscriptions_id_seq OWNED BY inscriptions.id;


--
-- Name: system_access_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_access_log (
    id integer NOT NULL,
    sessionid text,
    login text,
    login_time timestamp without time zone,
    logout_time timestamp without time zone
);


ALTER TABLE system_access_log OWNER TO postgres;

--
-- Name: system_change_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_change_log (
    id integer NOT NULL,
    logdate timestamp without time zone,
    login text,
    tablename text,
    primarykey text,
    pkvalue text,
    operation text,
    columnname text,
    oldvalue text,
    newvalue text
);


ALTER TABLE system_change_log OWNER TO postgres;

--
-- Name: system_group; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_group (
    id integer NOT NULL,
    name character varying(100)
);


ALTER TABLE system_group OWNER TO postgres;

--
-- Name: system_group_program; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_group_program (
    id integer NOT NULL,
    system_group_id integer NOT NULL,
    system_program_id integer NOT NULL
);


ALTER TABLE system_group_program OWNER TO postgres;

--
-- Name: system_program; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_program (
    id integer NOT NULL,
    name character varying(100),
    controller character varying(100)
);


ALTER TABLE system_program OWNER TO postgres;

--
-- Name: system_sql_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_sql_log (
    id integer NOT NULL,
    logdate timestamp without time zone,
    login text,
    database_name text,
    sql_command text,
    statement_type text
);


ALTER TABLE system_sql_log OWNER TO postgres;

--
-- Name: system_user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_user (
    id integer NOT NULL,
    name character varying(100),
    login character varying(100),
    password character varying(100),
    email character varying(100),
    frontpage_id integer NOT NULL
);


ALTER TABLE system_user OWNER TO postgres;

--
-- Name: system_user_group; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_user_group (
    id integer NOT NULL,
    system_user_id integer NOT NULL,
    system_group_id integer NOT NULL
);


ALTER TABLE system_user_group OWNER TO postgres;

--
-- Name: system_user_program; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE system_user_program (
    id integer NOT NULL,
    system_user_id integer NOT NULL,
    system_program_id integer NOT NULL
);


ALTER TABLE system_user_program OWNER TO postgres;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activities ALTER COLUMN id SET DEFAULT nextval('activities_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY attachments ALTER COLUMN id SET DEFAULT nextval('attachments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY events ALTER COLUMN id SET DEFAULT nextval('events_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY inscriptions ALTER COLUMN id SET DEFAULT nextval('inscriptions_id_seq'::regclass);


--
-- Data for Name: activities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY activities (id, name, dt_start, dt_end, local_name, local_geolocation, event_id) FROM stdin;
1	dsa	2017-05-13 00:20:00	2017-05-13 00:20:00	ads	das	4
\.


--
-- Name: activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('activities_id_seq', 1, true);


--
-- Data for Name: attachments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY attachments (id, name, size, local, activity_id, type) FROM stdin;
3	caso.png	91.91 KB	http://jacksonmajolo.ml/eventtus//attachments/1<-->caso.png	1	0
4	Client.java	944 bytes	http://jacksonmajolo.ml/eventtus//attachments/1<-->Client.java	1	4
5	Link 1 - dsa	0 b	http://legendaoficial.net/	1	2
\.


--
-- Name: attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('attachments_id_seq', 5, true);


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY events (id, name, banner, contact_name, contact_phone, contact_mail, description, dt_start, dt_end) FROM stdin;
4	Teste	dasdsdasdadssadasa	dasd	das	dasd	ads	2017-05-25 00:00:00	2017-05-19 00:00:00
2	Evento 11	dasd213	3	213	312	dasd	2017-05-13 00:00:00	2017-05-31 00:00:00
\.


--
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('events_id_seq', 4, true);


--
-- Data for Name: inscriptions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY inscriptions (id, email, hash, event_id) FROM stdin;
3	lcstomasi@gmail.com	123	4
\.


--
-- Name: inscriptions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('inscriptions_id_seq', 3, true);


--
-- Data for Name: system_access_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_access_log (id, sessionid, login, login_time, logout_time) FROM stdin;
1	oonri710fkk2kkdj6tpk9l9kr7	admin	2017-05-01 13:09:31	2017-05-01 13:19:37
2	crdt1r6b1biudn731pts4ba3b5	admin	2017-05-01 13:20:28	2017-05-01 14:43:33
3	okk9g4t2hle7q1kbo15oqd1pq6	admin	2017-05-01 14:43:37	\N
4	2vgjgkoss4e4lof3bh4mbr3rb7	admin	2017-05-01 16:06:40	2017-05-01 17:26:49
5	q0o7vjifv1vamiocbqa4dfhvs5	admin	2017-05-01 17:26:58	2017-05-01 17:28:04
6	a7lub34o01gstji6c8muhunil7	admin	2017-05-01 17:28:08	2017-05-01 17:29:08
7	ga0613o7d8go9re18d67rsp344	admin	2017-05-01 17:29:14	2017-05-01 17:35:27
8	75nu7kqbc4j5jfj63g4iijhsa4	admin	2017-05-01 17:37:50	\N
\.


--
-- Data for Name: system_change_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_change_log (id, logdate, login, tablename, primarykey, pkvalue, operation, columnname, oldvalue, newvalue) FROM stdin;
1	2017-05-01 13:13:58	admin	system_program	id	8	created	id	\N	8
2	2017-05-01 13:13:58	admin	system_program	id	8	created	name	\N	LIST - Event
3	2017-05-01 13:13:58	admin	system_program	id	8	created	controller	\N	EventList
4	2017-05-01 13:14:12	admin	system_program	id	9	created	id	\N	9
5	2017-05-01 13:14:12	admin	system_program	id	9	created	name	\N	FORM - Event
6	2017-05-01 13:14:12	admin	system_program	id	9	created	controller	\N	EventForm
7	2017-05-01 13:18:20	admin	system_group_program	id	1	created	system_program_id	\N	1
8	2017-05-01 13:18:20	admin	system_group_program	id	1	created	system_group_id	\N	1
9	2017-05-01 13:18:20	admin	system_group_program	id	1	created	id	\N	1
10	2017-05-01 13:18:20	admin	system_group_program	id	2	created	system_program_id	\N	2
11	2017-05-01 13:18:20	admin	system_group_program	id	2	created	system_group_id	\N	1
12	2017-05-01 13:18:20	admin	system_group_program	id	2	created	id	\N	2
13	2017-05-01 13:18:20	admin	system_group_program	id	3	created	system_program_id	\N	3
14	2017-05-01 13:18:20	admin	system_group_program	id	3	created	system_group_id	\N	1
15	2017-05-01 13:18:20	admin	system_group_program	id	3	created	id	\N	3
16	2017-05-01 13:18:20	admin	system_group_program	id	4	created	system_program_id	\N	4
17	2017-05-01 13:18:20	admin	system_group_program	id	4	created	system_group_id	\N	1
18	2017-05-01 13:18:20	admin	system_group_program	id	4	created	id	\N	4
19	2017-05-01 13:18:20	admin	system_group_program	id	5	created	system_program_id	\N	5
20	2017-05-01 13:18:20	admin	system_group_program	id	5	created	system_group_id	\N	1
21	2017-05-01 13:18:20	admin	system_group_program	id	5	created	id	\N	5
22	2017-05-01 13:18:20	admin	system_group_program	id	6	created	system_program_id	\N	6
23	2017-05-01 13:18:20	admin	system_group_program	id	6	created	system_group_id	\N	1
24	2017-05-01 13:18:20	admin	system_group_program	id	6	created	id	\N	6
25	2017-05-01 13:18:20	admin	system_group_program	id	7	created	system_program_id	\N	8
26	2017-05-01 13:18:20	admin	system_group_program	id	7	created	system_group_id	\N	1
27	2017-05-01 13:18:20	admin	system_group_program	id	7	created	id	\N	7
28	2017-05-01 13:18:20	admin	system_group_program	id	8	created	system_program_id	\N	9
29	2017-05-01 13:18:20	admin	system_group_program	id	8	created	system_group_id	\N	1
30	2017-05-01 13:18:20	admin	system_group_program	id	8	created	id	\N	8
31	2017-05-01 13:19:30	admin	system_group_program	id	1	created	system_program_id	\N	1
32	2017-05-01 13:19:30	admin	system_group_program	id	1	created	system_group_id	\N	1
33	2017-05-01 13:19:30	admin	system_group_program	id	1	created	id	\N	1
34	2017-05-01 13:19:30	admin	system_group_program	id	2	created	system_program_id	\N	2
35	2017-05-01 13:19:30	admin	system_group_program	id	2	created	system_group_id	\N	1
36	2017-05-01 13:19:30	admin	system_group_program	id	2	created	id	\N	2
37	2017-05-01 13:19:30	admin	system_group_program	id	3	created	system_program_id	\N	3
38	2017-05-01 13:19:30	admin	system_group_program	id	3	created	system_group_id	\N	1
39	2017-05-01 13:19:30	admin	system_group_program	id	3	created	id	\N	3
40	2017-05-01 13:19:30	admin	system_group_program	id	4	created	system_program_id	\N	4
41	2017-05-01 13:19:30	admin	system_group_program	id	4	created	system_group_id	\N	1
42	2017-05-01 13:19:30	admin	system_group_program	id	4	created	id	\N	4
43	2017-05-01 13:19:30	admin	system_group_program	id	5	created	system_program_id	\N	5
44	2017-05-01 13:19:30	admin	system_group_program	id	5	created	system_group_id	\N	1
45	2017-05-01 13:19:30	admin	system_group_program	id	5	created	id	\N	5
46	2017-05-01 13:19:30	admin	system_group_program	id	6	created	system_program_id	\N	6
47	2017-05-01 13:19:30	admin	system_group_program	id	6	created	system_group_id	\N	1
48	2017-05-01 13:19:30	admin	system_group_program	id	6	created	id	\N	6
49	2017-05-01 13:19:30	admin	system_group_program	id	7	created	system_program_id	\N	8
50	2017-05-01 13:19:30	admin	system_group_program	id	7	created	system_group_id	\N	1
51	2017-05-01 13:19:30	admin	system_group_program	id	7	created	id	\N	7
52	2017-05-01 13:19:30	admin	system_group_program	id	8	created	system_program_id	\N	9
53	2017-05-01 13:19:30	admin	system_group_program	id	8	created	system_group_id	\N	1
54	2017-05-01 13:19:30	admin	system_group_program	id	8	created	id	\N	8
55	2017-05-01 13:36:05	admin	public.events	id	1	created	name	\N	Evento 1
56	2017-05-01 13:36:05	admin	public.events	id	1	created	id	\N	1
57	2017-05-01 13:36:15	admin	public.events	id	2	created	name	\N	Evento 1
58	2017-05-01 13:36:15	admin	public.events	id	2	created	id	\N	2
59	2017-05-01 13:37:45	admin	public.events	id	1	deleted	id	1	\N
60	2017-05-01 13:37:45	admin	public.events	id	1	deleted	name	Evento 1	\N
61	2017-05-01 13:37:51	admin	public.events	id	3	created	name	\N	Evento 1
62	2017-05-01 13:37:51	admin	public.events	id	3	created	id	\N	3
63	2017-05-01 13:37:59	admin	public.events	id	3	deleted	id	3	\N
64	2017-05-01 13:37:59	admin	public.events	id	3	deleted	name	Evento 1	\N
65	2017-05-01 13:40:50	admin	public.events	id	4	created	name	\N	Teste
66	2017-05-01 13:40:50	admin	public.events	id	4	created	id	\N	4
67	2017-05-01 13:42:56	admin	public.events	id	2	changed	name	Evento 1	Evento 11
68	2017-05-01 14:02:45	admin	public.events	id	4	created	banner	\N	dasdsdasdadssadasa
69	2017-05-01 14:02:45	admin	public.events	id	4	created	dt_start	\N	2017-05-25
70	2017-05-01 14:02:45	admin	public.events	id	4	created	dt_end	\N	2017-05-19
71	2017-05-01 14:02:45	admin	public.events	id	4	created	contact_name	\N	dasd
72	2017-05-01 14:02:45	admin	public.events	id	4	created	contact_phone	\N	das
73	2017-05-01 14:02:45	admin	public.events	id	4	created	contact_mail	\N	dasd
74	2017-05-01 14:05:54	admin	public.events	id	4	created	description	\N	ads
75	2017-05-01 14:37:59	admin	public.events	id	2	created	banner	\N	dasd213
76	2017-05-01 14:37:59	admin	public.events	id	2	created	dt_start	\N	2017-05-13
77	2017-05-01 14:37:59	admin	public.events	id	2	created	dt_end	\N	2017-05-31
78	2017-05-01 14:37:59	admin	public.events	id	2	created	contact_name	\N	3
79	2017-05-01 14:37:59	admin	public.events	id	2	created	contact_phone	\N	213
80	2017-05-01 14:37:59	admin	public.events	id	2	created	contact_mail	\N	312
81	2017-05-01 14:37:59	admin	public.events	id	2	created	description	\N	dasd
82	2017-05-01 14:42:50	admin	system_program	id	10	created	id	\N	10
83	2017-05-01 14:42:50	admin	system_program	id	10	created	name	\N	LIST - Activities
84	2017-05-01 14:42:50	admin	system_program	id	10	created	controller	\N	ActivityList
85	2017-05-01 14:43:13	admin	system_program	id	11	created	id	\N	11
86	2017-05-01 14:43:13	admin	system_program	id	11	created	name	\N	FORM - Activity
87	2017-05-01 14:43:13	admin	system_program	id	11	created	controller	\N	ActivityForm
88	2017-05-01 14:43:27	admin	system_group_program	id	1	created	system_program_id	\N	1
89	2017-05-01 14:43:27	admin	system_group_program	id	1	created	system_group_id	\N	1
90	2017-05-01 14:43:27	admin	system_group_program	id	1	created	id	\N	1
91	2017-05-01 14:43:27	admin	system_group_program	id	2	created	system_program_id	\N	2
92	2017-05-01 14:43:27	admin	system_group_program	id	2	created	system_group_id	\N	1
93	2017-05-01 14:43:27	admin	system_group_program	id	2	created	id	\N	2
94	2017-05-01 14:43:27	admin	system_group_program	id	3	created	system_program_id	\N	3
95	2017-05-01 14:43:27	admin	system_group_program	id	3	created	system_group_id	\N	1
96	2017-05-01 14:43:27	admin	system_group_program	id	3	created	id	\N	3
97	2017-05-01 14:43:27	admin	system_group_program	id	4	created	system_program_id	\N	4
98	2017-05-01 14:43:27	admin	system_group_program	id	4	created	system_group_id	\N	1
99	2017-05-01 14:43:27	admin	system_group_program	id	4	created	id	\N	4
100	2017-05-01 14:43:27	admin	system_group_program	id	5	created	system_program_id	\N	5
101	2017-05-01 14:43:27	admin	system_group_program	id	5	created	system_group_id	\N	1
102	2017-05-01 14:43:27	admin	system_group_program	id	5	created	id	\N	5
103	2017-05-01 14:43:27	admin	system_group_program	id	6	created	system_program_id	\N	6
104	2017-05-01 14:43:27	admin	system_group_program	id	6	created	system_group_id	\N	1
105	2017-05-01 14:43:27	admin	system_group_program	id	6	created	id	\N	6
106	2017-05-01 14:43:27	admin	system_group_program	id	7	created	system_program_id	\N	8
107	2017-05-01 14:43:27	admin	system_group_program	id	7	created	system_group_id	\N	1
108	2017-05-01 14:43:27	admin	system_group_program	id	7	created	id	\N	7
109	2017-05-01 14:43:27	admin	system_group_program	id	8	created	system_program_id	\N	9
110	2017-05-01 14:43:27	admin	system_group_program	id	8	created	system_group_id	\N	1
111	2017-05-01 14:43:27	admin	system_group_program	id	8	created	id	\N	8
112	2017-05-01 14:43:27	admin	system_group_program	id	9	created	system_program_id	\N	10
113	2017-05-01 14:43:27	admin	system_group_program	id	9	created	system_group_id	\N	1
114	2017-05-01 14:43:27	admin	system_group_program	id	9	created	id	\N	9
115	2017-05-01 14:43:27	admin	system_group_program	id	10	created	system_program_id	\N	11
116	2017-05-01 14:43:27	admin	system_group_program	id	10	created	system_group_id	\N	1
117	2017-05-01 14:43:27	admin	system_group_program	id	10	created	id	\N	10
118	2017-05-01 15:04:28	admin	public.activities	id	1	created	name	\N	dsa
119	2017-05-01 15:04:28	admin	public.activities	id	1	created	local_geolocation	\N	das
120	2017-05-01 15:04:28	admin	public.activities	id	1	created	dt_start	\N	2017-05-13 00:20:00
121	2017-05-01 15:04:28	admin	public.activities	id	1	created	dt_end	\N	2017-05-13 00:20:00
122	2017-05-01 15:04:28	admin	public.activities	id	1	created	event_id	\N	4
123	2017-05-01 15:04:28	admin	public.activities	id	1	created	local_name	\N	ads
124	2017-05-01 15:04:28	admin	public.activities	id	1	created	id	\N	1
125	2017-05-01 16:56:15	admin	public.attachments	id	1	created	size	\N	91.91 KB
126	2017-05-01 16:56:15	admin	public.attachments	id	1	created	name	\N	caso.png
127	2017-05-01 16:56:15	admin	public.attachments	id	1	created	activity_id	\N	1
128	2017-05-01 16:56:15	admin	public.attachments	id	1	created	id	\N	1
129	2017-05-01 16:56:15	admin	public.attachments	id	2	created	size	\N	944 bytes
130	2017-05-01 16:56:15	admin	public.attachments	id	2	created	type	\N	4
131	2017-05-01 16:56:15	admin	public.attachments	id	2	created	name	\N	Client.java
132	2017-05-01 16:56:15	admin	public.attachments	id	2	created	activity_id	\N	1
133	2017-05-01 16:56:15	admin	public.attachments	id	2	created	id	\N	2
134	2017-05-01 17:01:33	admin	public.attachments	id	3	created	size	\N	91.91 KB
135	2017-05-01 17:01:33	admin	public.attachments	id	3	created	name	\N	caso.png
136	2017-05-01 17:01:33	admin	public.attachments	id	3	created	activity_id	\N	1
137	2017-05-01 17:01:33	admin	public.attachments	id	3	created	local	\N	http://jacksonmajolo.ml/eventtus//attachments/1<-->caso.png
138	2017-05-01 17:01:33	admin	public.attachments	id	3	created	id	\N	3
139	2017-05-01 17:01:33	admin	public.attachments	id	4	created	size	\N	944 bytes
140	2017-05-01 17:01:33	admin	public.attachments	id	4	created	type	\N	4
141	2017-05-01 17:01:33	admin	public.attachments	id	4	created	name	\N	Client.java
142	2017-05-01 17:01:33	admin	public.attachments	id	4	created	activity_id	\N	1
143	2017-05-01 17:01:33	admin	public.attachments	id	4	created	local	\N	http://jacksonmajolo.ml/eventtus//attachments/1<-->Client.java
144	2017-05-01 17:01:33	admin	public.attachments	id	4	created	id	\N	4
145	2017-05-01 17:09:11	admin	public.attachments	id	5	created	size	\N	0 b
146	2017-05-01 17:09:11	admin	public.attachments	id	5	created	type	\N	2
147	2017-05-01 17:09:11	admin	public.attachments	id	5	created	name	\N	Link 1 - dsa
148	2017-05-01 17:09:11	admin	public.attachments	id	5	created	activity_id	\N	1
149	2017-05-01 17:09:11	admin	public.attachments	id	5	created	local	\N	http://legendaoficial.net/
150	2017-05-01 17:09:11	admin	public.attachments	id	5	created	id	\N	5
151	2017-05-01 17:26:36	admin	system_program	id	12	created	id	\N	12
152	2017-05-01 17:26:36	admin	system_program	id	12	created	name	\N	FORMLIST - Inscriptions
153	2017-05-01 17:26:36	admin	system_program	id	12	created	controller	\N	Inscriptions
154	2017-05-01 17:26:45	admin	system_group_program	id	1	created	system_program_id	\N	1
155	2017-05-01 17:26:45	admin	system_group_program	id	1	created	system_group_id	\N	1
156	2017-05-01 17:26:45	admin	system_group_program	id	1	created	id	\N	1
157	2017-05-01 17:26:45	admin	system_group_program	id	2	created	system_program_id	\N	2
158	2017-05-01 17:26:45	admin	system_group_program	id	2	created	system_group_id	\N	1
159	2017-05-01 17:26:45	admin	system_group_program	id	2	created	id	\N	2
160	2017-05-01 17:26:45	admin	system_group_program	id	3	created	system_program_id	\N	3
161	2017-05-01 17:26:45	admin	system_group_program	id	3	created	system_group_id	\N	1
162	2017-05-01 17:26:45	admin	system_group_program	id	3	created	id	\N	3
163	2017-05-01 17:26:45	admin	system_group_program	id	4	created	system_program_id	\N	4
164	2017-05-01 17:26:45	admin	system_group_program	id	4	created	system_group_id	\N	1
165	2017-05-01 17:26:45	admin	system_group_program	id	4	created	id	\N	4
166	2017-05-01 17:26:45	admin	system_group_program	id	5	created	system_program_id	\N	5
167	2017-05-01 17:26:45	admin	system_group_program	id	5	created	system_group_id	\N	1
168	2017-05-01 17:26:45	admin	system_group_program	id	5	created	id	\N	5
169	2017-05-01 17:26:45	admin	system_group_program	id	6	created	system_program_id	\N	6
170	2017-05-01 17:26:45	admin	system_group_program	id	6	created	system_group_id	\N	1
171	2017-05-01 17:26:45	admin	system_group_program	id	6	created	id	\N	6
172	2017-05-01 17:26:45	admin	system_group_program	id	7	created	system_program_id	\N	8
173	2017-05-01 17:26:45	admin	system_group_program	id	7	created	system_group_id	\N	1
174	2017-05-01 17:26:45	admin	system_group_program	id	7	created	id	\N	7
175	2017-05-01 17:26:45	admin	system_group_program	id	8	created	system_program_id	\N	9
176	2017-05-01 17:26:45	admin	system_group_program	id	8	created	system_group_id	\N	1
177	2017-05-01 17:26:45	admin	system_group_program	id	8	created	id	\N	8
178	2017-05-01 17:26:45	admin	system_group_program	id	9	created	system_program_id	\N	10
179	2017-05-01 17:26:45	admin	system_group_program	id	9	created	system_group_id	\N	1
180	2017-05-01 17:26:45	admin	system_group_program	id	9	created	id	\N	9
181	2017-05-01 17:26:45	admin	system_group_program	id	10	created	system_program_id	\N	11
182	2017-05-01 17:26:45	admin	system_group_program	id	10	created	system_group_id	\N	1
183	2017-05-01 17:26:45	admin	system_group_program	id	10	created	id	\N	10
184	2017-05-01 17:26:45	admin	system_group_program	id	11	created	system_program_id	\N	12
185	2017-05-01 17:26:45	admin	system_group_program	id	11	created	system_group_id	\N	1
186	2017-05-01 17:26:45	admin	system_group_program	id	11	created	id	\N	11
187	2017-05-01 17:27:58	admin	system_program	id	12	changed	controller	Inscriptions	Inscription
188	2017-05-01 17:29:06	admin	system_program	id	12	changed	controller	Inscription	InscriptionFormList
189	2017-05-01 17:33:44	admin	public.inscriptions	id	1	created	email	\N	lcstomasi@gmail.com
190	2017-05-01 17:33:44	admin	public.inscriptions	id	1	created	hash	\N	dasd1223odidashd
191	2017-05-01 17:33:44	admin	public.inscriptions	id	1	created	event_id	\N	4
192	2017-05-01 17:33:44	admin	public.inscriptions	id	1	created	id	\N	1
193	2017-05-01 17:33:53	admin	public.inscriptions	id	1	deleted	id	1	\N
194	2017-05-01 17:33:53	admin	public.inscriptions	id	1	deleted	email	lcstomasi@gmail.com	\N
195	2017-05-01 17:33:53	admin	public.inscriptions	id	1	deleted	hash	dasd1223odidashd	\N
196	2017-05-01 17:33:53	admin	public.inscriptions	id	1	deleted	event_id	4	\N
197	2017-05-01 17:38:20	admin	public.inscriptions	id	2	created	email	\N	lcstomasi@gmail.com
198	2017-05-01 17:38:20	admin	public.inscriptions	id	2	created	hash	\N	dlçakjdpeh12
199	2017-05-01 17:38:20	admin	public.inscriptions	id	2	created	event_id	\N	4
200	2017-05-01 17:38:20	admin	public.inscriptions	id	2	created	id	\N	2
201	2017-05-01 18:06:17	admin	public.inscriptions	id	2	deleted	id	2	\N
202	2017-05-01 18:06:17	admin	public.inscriptions	id	2	deleted	email	lcstomasi@gmail.com	\N
203	2017-05-01 18:06:17	admin	public.inscriptions	id	2	deleted	hash	dlçakjdpeh12	\N
204	2017-05-01 18:06:17	admin	public.inscriptions	id	2	deleted	event_id	4	\N
205	2017-05-01 18:06:34	admin	public.inscriptions	id	3	created	email	\N	lcstomasi@gmail.com
206	2017-05-01 18:06:34	admin	public.inscriptions	id	3	created	hash	\N	123
207	2017-05-01 18:06:34	admin	public.inscriptions	id	3	created	event_id	\N	4
208	2017-05-01 18:06:34	admin	public.inscriptions	id	3	created	id	\N	3
\.


--
-- Data for Name: system_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_group (id, name) FROM stdin;
1	ADMIN
\.


--
-- Data for Name: system_group_program; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_group_program (id, system_group_id, system_program_id) FROM stdin;
1	1	1
2	1	2
3	1	3
4	1	4
5	1	5
6	1	6
7	1	8
8	1	9
9	1	10
10	1	11
11	1	12
\.


--
-- Data for Name: system_program; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_program (id, name, controller) FROM stdin;
1	System Group Form	SystemGroupForm
2	System Group List	SystemGroupList
3	System Program Form	SystemProgramForm
4	System Program List	SystemProgramList
5	System User Form	SystemUserForm
6	System User List	SystemUserList
7	Common Page	CommonPage
8	LIST - Event	EventList
9	FORM - Event	EventForm
10	LIST - Activities	ActivityList
11	FORM - Activity	ActivityForm
12	FORMLIST - Inscriptions	InscriptionFormList
\.


--
-- Data for Name: system_sql_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_sql_log (id, logdate, login, database_name, sql_command, statement_type) FROM stdin;
1	2017-05-01 13:13:58	admin	permission	INSERT INTO system_program (id, name, controller) values (8, 'LIST - Event', 'EventList')	INSERT
2	2017-05-01 13:14:12	admin	permission	INSERT INTO system_program (id, name, controller) values (9, 'FORM - Event', 'EventForm')	INSERT
3	2017-05-01 13:18:20	admin	permission	UPDATE system_group SET name = 'ADMIN' WHERE (id = '1')	UPDATE
4	2017-05-01 13:18:20	admin	permission	DELETE FROM system_group_program WHERE (system_group_id = '1')	DELETE
5	2017-05-01 13:18:20	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('1', '1', 1)	INSERT
6	2017-05-01 13:18:20	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('2', '1', 2)	INSERT
7	2017-05-01 13:18:20	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('3', '1', 3)	INSERT
8	2017-05-01 13:18:20	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('4', '1', 4)	INSERT
9	2017-05-01 13:18:20	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('5', '1', 5)	INSERT
10	2017-05-01 13:18:20	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('6', '1', 6)	INSERT
11	2017-05-01 13:18:20	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('8', '1', 7)	INSERT
12	2017-05-01 13:18:20	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('9', '1', 8)	INSERT
13	2017-05-01 13:19:30	admin	permission	UPDATE system_group SET name = 'ADMIN' WHERE (id = '1')	UPDATE
14	2017-05-01 13:19:30	admin	permission	DELETE FROM system_group_program WHERE (system_group_id = '1')	DELETE
15	2017-05-01 13:19:30	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('1', '1', 1)	INSERT
16	2017-05-01 13:19:30	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('2', '1', 2)	INSERT
17	2017-05-01 13:19:30	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('3', '1', 3)	INSERT
18	2017-05-01 13:19:30	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('4', '1', 4)	INSERT
19	2017-05-01 13:19:30	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('5', '1', 5)	INSERT
20	2017-05-01 13:19:30	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('6', '1', 6)	INSERT
21	2017-05-01 13:19:30	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('8', '1', 7)	INSERT
22	2017-05-01 13:19:30	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('9', '1', 8)	INSERT
23	2017-05-01 13:36:05	admin	eventtus	INSERT INTO public.events (name) values ('Evento 1')	INSERT
24	2017-05-01 13:36:15	admin	eventtus	INSERT INTO public.events (name) values ('Evento 1')	INSERT
25	2017-05-01 13:37:45	admin	eventtus	DELETE FROM public.events WHERE (id = 1)	DELETE
26	2017-05-01 13:37:51	admin	eventtus	INSERT INTO public.events (name) values ('Evento 1')	INSERT
27	2017-05-01 13:37:59	admin	eventtus	DELETE FROM public.events WHERE (id = 3)	DELETE
28	2017-05-01 13:40:50	admin	eventtus	INSERT INTO public.events (name) values ('Teste')	INSERT
29	2017-05-01 13:42:50	admin	eventtus	UPDATE public.events SET name = 'Evento 1' WHERE (id = '2')	UPDATE
30	2017-05-01 13:42:56	admin	eventtus	UPDATE public.events SET name = 'Evento 11' WHERE (id = '2')	UPDATE
31	2017-05-01 14:02:45	admin	eventtus	UPDATE public.events SET name = 'Teste', banner = 'dasdsdasdadssadasa', dt_start = '2017-05-25', dt_end = '2017-05-19', contact_name = 'dasd', contact_phone = 'das', contact_mail = 'dasd' WHERE (id = '4')	UPDATE
32	2017-05-01 14:05:30	admin	eventtus	UPDATE public.events SET name = 'Teste', banner = 'dasdsdasdadssadasa', dt_start = '2017-05-25', dt_end = '2017-05-19', contact_name = 'dasd', contact_phone = 'das', contact_mail = 'dasd' WHERE (id = '4')	UPDATE
33	2017-05-01 14:05:37	admin	eventtus	UPDATE public.events SET name = 'Teste', banner = 'dasdsdasdadssadasa', dt_start = '2017-05-25', dt_end = '2017-05-19', contact_name = 'dasd', contact_phone = 'das', contact_mail = 'dasd' WHERE (id = '4')	UPDATE
34	2017-05-01 14:05:54	admin	eventtus	UPDATE public.events SET name = 'Teste', banner = 'dasdsdasdadssadasa', dt_start = '2017-05-25', dt_end = '2017-05-19', contact_name = 'dasd', contact_phone = 'das', contact_mail = 'dasd', description = 'ads' WHERE (id = '4')	UPDATE
35	2017-05-01 14:37:59	admin	eventtus	UPDATE public.events SET name = 'Evento 11', banner = 'dasd213', dt_start = '2017-05-13', dt_end = '2017-05-31', contact_name = '3', contact_phone = '213', contact_mail = '312', description = 'dasd' WHERE (id = '2')	UPDATE
36	2017-05-01 14:42:50	admin	permission	INSERT INTO system_program (id, name, controller) values (10, 'LIST - Activities', 'ActivityList')	INSERT
37	2017-05-01 14:43:13	admin	permission	INSERT INTO system_program (id, name, controller) values (11, 'FORM - Activity', 'ActivityForm')	INSERT
38	2017-05-01 14:43:26	admin	permission	UPDATE system_group SET name = 'ADMIN' WHERE (id = '1')	UPDATE
39	2017-05-01 14:43:27	admin	permission	DELETE FROM system_group_program WHERE (system_group_id = '1')	DELETE
40	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('1', '1', 1)	INSERT
41	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('2', '1', 2)	INSERT
42	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('3', '1', 3)	INSERT
43	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('4', '1', 4)	INSERT
44	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('5', '1', 5)	INSERT
45	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('6', '1', 6)	INSERT
46	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('8', '1', 7)	INSERT
47	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('9', '1', 8)	INSERT
48	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('10', '1', 9)	INSERT
49	2017-05-01 14:43:27	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('11', '1', 10)	INSERT
50	2017-05-01 15:04:00	admin	eventtus	INSERT INTO public.activities (name, local_geolocation, dt_start, dt_end, event_id, local_name) values ('dsa', 'das', 'da', 'asd', 'da', 'ads')	INSERT
51	2017-05-01 15:04:20	admin	eventtus	INSERT INTO public.activities (name, local_geolocation, dt_start, dt_end, event_id, local_name) values ('dsa', 'das', '2017-05-13 00:20:00', '2017-05-13 00:20:00', 'da', 'ads')	INSERT
52	2017-05-01 15:04:28	admin	eventtus	INSERT INTO public.activities (name, local_geolocation, dt_start, dt_end, event_id, local_name) values ('dsa', 'das', '2017-05-13 00:20:00', '2017-05-13 00:20:00', '4', 'ads')	INSERT
53	2017-05-01 16:09:22	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
54	2017-05-01 16:09:38	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
55	2017-05-01 16:10:58	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
56	2017-05-01 16:12:32	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
57	2017-05-01 16:13:21	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
58	2017-05-01 16:14:09	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
59	2017-05-01 16:14:42	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
60	2017-05-01 16:29:17	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
61	2017-05-01 16:30:16	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
62	2017-05-01 16:56:15	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
63	2017-05-01 16:56:15	admin	eventtus	INSERT INTO public.attachments (size, type, name, activity_id) values ('91.91 KB', 0, 'caso.png', '1')	INSERT
64	2017-05-01 16:56:15	admin	eventtus	INSERT INTO public.attachments (size, type, name, activity_id) values ('944 bytes', 4, 'Client.java', '1')	INSERT
65	2017-05-01 17:01:33	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
66	2017-05-01 17:01:33	admin	eventtus	INSERT INTO public.attachments (size, type, name, activity_id, local) values ('91.91 KB', 0, 'caso.png', '1', 'http://jacksonmajolo.ml/eventtus//attachments/1<-->caso.png')	INSERT
67	2017-05-01 17:01:33	admin	eventtus	INSERT INTO public.attachments (size, type, name, activity_id, local) values ('944 bytes', 4, 'Client.java', '1', 'http://jacksonmajolo.ml/eventtus//attachments/1<-->Client.java')	INSERT
68	2017-05-01 17:09:11	admin	eventtus	UPDATE public.activities SET name = 'dsa', local_geolocation = 'das', dt_start = '2017-05-13 00:20:00', dt_end = '2017-05-13 00:20:00', event_id = '4', local_name = 'ads' WHERE (id = '1')	UPDATE
69	2017-05-01 17:09:11	admin	eventtus	INSERT INTO public.attachments (size, type, name, activity_id, local) values ('0 b', 2, 'Link 1 - dsa', '1', 'http://legendaoficial.net/')	INSERT
70	2017-05-01 17:26:36	admin	permission	INSERT INTO system_program (id, name, controller) values (12, 'FORMLIST - Inscriptions', 'Inscriptions')	INSERT
71	2017-05-01 17:26:45	admin	permission	UPDATE system_group SET name = 'ADMIN' WHERE (id = '1')	UPDATE
72	2017-05-01 17:26:45	admin	permission	DELETE FROM system_group_program WHERE (system_group_id = '1')	DELETE
73	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('1', '1', 1)	INSERT
74	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('2', '1', 2)	INSERT
75	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('3', '1', 3)	INSERT
76	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('4', '1', 4)	INSERT
77	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('5', '1', 5)	INSERT
78	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('6', '1', 6)	INSERT
79	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('8', '1', 7)	INSERT
80	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('9', '1', 8)	INSERT
81	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('10', '1', 9)	INSERT
82	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('11', '1', 10)	INSERT
83	2017-05-01 17:26:45	admin	permission	INSERT INTO system_group_program (system_program_id, system_group_id, id) values ('12', '1', 11)	INSERT
84	2017-05-01 17:27:58	admin	permission	UPDATE system_program SET name = 'FORMLIST - Inscriptions', controller = 'Inscription' WHERE (id = '12')	UPDATE
85	2017-05-01 17:29:06	admin	permission	UPDATE system_program SET name = 'FORMLIST - Inscriptions', controller = 'InscriptionFormList' WHERE (id = '12')	UPDATE
86	2017-05-01 17:33:44	admin	eventtus	INSERT INTO public.inscriptions (email, hash, event_id) values ('lcstomasi@gmail.com', 'dasd1223odidashd', '4')	INSERT
87	2017-05-01 17:33:53	admin	eventtus	DELETE FROM public.inscriptions WHERE (id = 1)	DELETE
88	2017-05-01 17:38:20	admin	eventtus	INSERT INTO public.inscriptions (email, hash, event_id) values ('lcstomasi@gmail.com', 'dlçakjdpeh12', '4')	INSERT
89	2017-05-01 18:06:17	admin	eventtus	DELETE FROM public.inscriptions WHERE (id = 2)	DELETE
90	2017-05-01 18:06:34	admin	eventtus	INSERT INTO public.inscriptions (email, hash, event_id) values ('lcstomasi@gmail.com', '123', '4')	INSERT
\.


--
-- Data for Name: system_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_user (id, name, login, password, email, frontpage_id) FROM stdin;
1	Administrator	admin	21232f297a57a5a743894a0e4a801fc3	admin@admin.net	6
2	User	user	ee11cbb19052e40b07aac0ca060c23ee	user@user.net	7
\.


--
-- Data for Name: system_user_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_user_group (id, system_user_id, system_group_id) FROM stdin;
1	1	1
\.


--
-- Data for Name: system_user_program; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_user_program (id, system_user_id, system_program_id) FROM stdin;
1	2	7
\.


--
-- Name: activities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activities
    ADD CONSTRAINT activities_pkey PRIMARY KEY (id);


--
-- Name: attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY attachments
    ADD CONSTRAINT attachments_pkey PRIMARY KEY (id);


--
-- Name: events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id);


--
-- Name: inscriptions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY inscriptions
    ADD CONSTRAINT inscriptions_pkey PRIMARY KEY (id);


--
-- Name: system_access_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_access_log
    ADD CONSTRAINT system_access_log_pkey PRIMARY KEY (id);


--
-- Name: system_change_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_change_log
    ADD CONSTRAINT system_change_log_pkey PRIMARY KEY (id);


--
-- Name: system_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group
    ADD CONSTRAINT system_group_pkey PRIMARY KEY (id);


--
-- Name: system_group_program_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group_program
    ADD CONSTRAINT system_group_program_pkey PRIMARY KEY (id);


--
-- Name: system_program_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_program
    ADD CONSTRAINT system_program_pkey PRIMARY KEY (id);


--
-- Name: system_sql_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_sql_log
    ADD CONSTRAINT system_sql_log_pkey PRIMARY KEY (id);


--
-- Name: system_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_group
    ADD CONSTRAINT system_user_group_pkey PRIMARY KEY (id);


--
-- Name: system_user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user
    ADD CONSTRAINT system_user_pkey PRIMARY KEY (id);


--
-- Name: system_user_program_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_program
    ADD CONSTRAINT system_user_program_pkey PRIMARY KEY (id);


--
-- Name: system_group_program_fk_system_group_has_system_group1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX system_group_program_fk_system_group_has_system_group1 ON system_group_program USING btree (system_group_id);


--
-- Name: system_group_program_fk_system_group_has_system_program1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX system_group_program_fk_system_group_has_system_program1 ON system_group_program USING btree (system_program_id);


--
-- Name: system_user_fk_system_user_system_program1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX system_user_fk_system_user_system_program1 ON system_user USING btree (frontpage_id);


--
-- Name: system_user_group_fk_system_user_has_system_group_system_group1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX system_user_group_fk_system_user_has_system_group_system_group1 ON system_user_group USING btree (system_group_id);


--
-- Name: system_user_group_fk_system_user_has_system_group_system_user; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX system_user_group_fk_system_user_has_system_group_system_user ON system_user_group USING btree (system_user_id);


--
-- Name: system_user_program_fk_system_user_has_system_program_system_pr; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX system_user_program_fk_system_user_has_system_program_system_pr ON system_user_program USING btree (system_program_id);


--
-- Name: system_user_program_fk_system_user_has_system_program_system_us; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX system_user_program_fk_system_user_has_system_program_system_us ON system_user_program USING btree (system_user_id);


--
-- Name: fk_system_group_has_system_program_system_group1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group_program
    ADD CONSTRAINT fk_system_group_has_system_program_system_group1 FOREIGN KEY (system_group_id) REFERENCES system_group(id);


--
-- Name: fk_system_group_has_system_program_system_program1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group_program
    ADD CONSTRAINT fk_system_group_has_system_program_system_program1 FOREIGN KEY (system_program_id) REFERENCES system_program(id);


--
-- Name: fk_system_user_has_system_group_system_group1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_group
    ADD CONSTRAINT fk_system_user_has_system_group_system_group1 FOREIGN KEY (system_group_id) REFERENCES system_group(id);


--
-- Name: fk_system_user_has_system_group_system_user; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_group
    ADD CONSTRAINT fk_system_user_has_system_group_system_user FOREIGN KEY (system_user_id) REFERENCES system_user(id);


--
-- Name: fk_system_user_has_system_program_system_program1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_program
    ADD CONSTRAINT fk_system_user_has_system_program_system_program1 FOREIGN KEY (system_program_id) REFERENCES system_program(id);


--
-- Name: fk_system_user_has_system_program_system_user1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_program
    ADD CONSTRAINT fk_system_user_has_system_program_system_user1 FOREIGN KEY (system_user_id) REFERENCES system_user(id);


--
-- Name: fk_system_user_system_program1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user
    ADD CONSTRAINT fk_system_user_system_program1 FOREIGN KEY (frontpage_id) REFERENCES system_program(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

