--
-- PostgreSQL database dump
--

-- Dumped from database version 15.3
-- Dumped by pg_dump version 15.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: products; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.products (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    value real DEFAULT 0.00 NOT NULL,
    id_type integer NOT NULL,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.products OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.products_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_id_seq OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: products_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.products_types (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    tax real DEFAULT 0.00 NOT NULL
);


ALTER TABLE public.products_types OWNER TO postgres;

--
-- Name: products_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.products_types_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_types_id_seq OWNER TO postgres;

--
-- Name: products_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.products_types_id_seq OWNED BY public.products_types.id;


--
-- Name: sales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sales (
    id integer NOT NULL,
    product_name character varying(255) NOT NULL,
    total_price_products real DEFAULT 0 NOT NULL,
    total_price_taxes real DEFAULT 0 NOT NULL,
    final_price real DEFAULT 0 NOT NULL,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    id_user integer NOT NULL
);


ALTER TABLE public.sales OWNER TO postgres;

--
-- Name: sales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sales_id_seq OWNER TO postgres;

--
-- Name: sales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sales_id_seq OWNED BY public.sales.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    admin boolean DEFAULT false NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: products_types id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products_types ALTER COLUMN id SET DEFAULT nextval('public.products_types_id_seq'::regclass);


--
-- Name: sales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales ALTER COLUMN id SET DEFAULT nextval('public.sales_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.products (id, name, value, id_type, active, created_at) FROM stdin;
1	Apple	5.29	8	t	2023-07-09 14:11:05.077088
2	Banana	5	8	t	2023-07-11 19:42:16.10582
3	Pear	5	8	t	2023-07-11 22:12:02.187977
4	Pineaple	5	8	t	2023-07-11 23:34:47.51956
6	Grape	5	8	f	2023-07-11 23:36:27.176072
5	Floor Cleaner	53.99	11	t	2023-07-11 23:35:10.590562
8	Shampoo Whiteclean	53.99	11	t	2023-07-13 22:22:37.166816
7	Watermelon	19	8	t	2023-07-13 22:21:50.77732
\.


--
-- Data for Name: products_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.products_types (id, name, active, created_at, tax) FROM stdin;
10	tools	t	2023-07-09 13:51:50.101967	0.1
9	food	t	2023-07-09 13:51:50.101967	0.5
12	automotive	t	2023-07-11 23:55:22.630533	0.7
11	cleaning	f	2023-07-09 13:51:50.101967	0.3
13	Electronics	t	2023-07-13 22:34:58.09153	0.7
8	Fruits	t	2023-07-09 13:51:50.101967	0.2
\.


--
-- Data for Name: sales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sales (id, product_name, total_price_products, total_price_taxes, final_price, active, created_at, id_user) FROM stdin;
3	Apple	52.9	13.225	66.125	t	2023-07-12 01:02:15.436468	1
5	Apple	63.48	15.87	79.35	t	2023-07-12 01:02:38.245236	1
6	Floor Cleaner	203.96	61.188	265.148	t	2023-07-12 01:02:38.245236	1
10	Apple	5.29	1.3225	6.6125	t	2023-07-13 20:32:40.989758	1
11	Apple	5.29	1.3225	6.6125	t	2023-07-13 20:32:40.989758	1
12	Apple	5.29	1.3225	6.6125	t	2023-07-13 20:32:40.989758	1
13	Apple	5.29	1.3225	6.6125	t	2023-07-13 20:32:40.989758	1
14	Floor Cleaner	50.99	15.297	66.287	t	2023-07-13 20:32:40.989758	1
15	Apple	5.29	1.3225	6.6125	t	2023-07-13 20:34:53.537429	1
16	Apple	5.29	1.3225	6.6125	t	2023-07-13 20:34:53.537429	1
17	Apple	5.29	1.3225	6.6125	t	2023-07-13 20:34:53.537429	1
18	Apple	5.29	1.3225	6.6125	t	2023-07-13 20:34:53.537429	1
19	Floor Cleaner	50.99	15.297	66.287	t	2023-07-13 20:34:53.537429	1
4	Floor Cleaner	50.99	15.297	66.287	f	2023-07-12 01:02:15.436468	1
20	Shampoo Whiteclean	53.99	16.197	70.187	t	2023-07-13 22:51:08.130414	1
21	Floor Cleaner	53.99	16.197	70.187	t	2023-07-13 22:51:08.130414	1
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, username, password, active, created_at, admin) FROM stdin;
1	admin	$2y$10$iWANtAjwRKU2a.KoRJ.qSeNw4fy7p/vNurVr4ItVZlg/dkqI/buY2	t	2023-07-09 11:51:45.557986	t
21	rafa	$2y$10$n10ROB82rIY93RNo1REp9uUeyyP9juQit2wwUBTCr.9LBkCF0ulSS	t	2023-07-11 12:07:39.837558	f
\.


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.products_id_seq', 8, true);


--
-- Name: products_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.products_types_id_seq', 13, true);


--
-- Name: sales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sales_id_seq', 21, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 24, true);


--
-- Name: products unique_product_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT unique_product_id UNIQUE (id);


--
-- Name: products_types unique_product_type_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products_types
    ADD CONSTRAINT unique_product_type_id UNIQUE (id);


--
-- Name: sales unique_sale_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT unique_sale_id UNIQUE (id);


--
-- Name: users unique_user_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_user_id UNIQUE (id);


--
-- Name: users unique_username; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_username UNIQUE (username);


--
-- PostgreSQL database dump complete
--

