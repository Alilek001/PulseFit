\begin{titlepage}
\centering

\vspace*{2cm}

{\Large\textbf{IES SERRA PERENXISA}}

\vspace{0.4cm}

{\large Ciclo Formativo de Grado Superior}

{\large Desarrollo de Aplicaciones Web (DAW)}

\vspace{1cm}

\noindent\rule{10cm}{0.4pt}

\vspace{2cm}

{\Huge\textbf{PulseFit}}

\vspace{0.6cm}

{\large Plataforma web inteligente de entrenamiento y nutrición fitness}

\vspace{1.2cm}

{\large\textbf{MEMORIA PROYECTO FIN DE CICLO}}

\vspace{0.3cm}

{\large Desarrollo de Aplicaciones Web (DAW)}

\vspace{2.5cm}

{\large\textbf{Ali Lekhal Hanifi}}

\vspace{0.5cm}

{\large Tutora: Natalia Escrivá Núñez}

\vspace{0.2cm}

{\large Centro: IES Serra Perenxisa}

\vspace{0.2cm}

{\large Curso: 2025--2026}

\vspace{1cm}

{\large\textit{Valencia, junio de 2026}}

\end{titlepage}

\newpage
\tableofcontents
\newpage

---

## RESUMEN

### Español

PulseFit es una aplicación web de rendimiento físico orientada a atletas y usuarios que desean llevar un seguimiento riguroso de su entrenamiento y nutrición. El sistema implementa un modelo de suscripción por niveles (Básico y Élite) gestionado por un administrador, con funcionalidades que incluyen catálogo de rutinas, plan semanal personalizado, registro nutricional diario, historial de entrenamientos, progreso de peso y recetas. El backend se desarrolla con Laravel 13 y la API REST se asegura mediante Laravel Sanctum; el frontend se construye con Vue.js 3 (Composition API) y Tailwind CSS v4, desplegándose el sistema en Render (backend) y Vercel (frontend).

### Valencià

PulseFit és una aplicació web de rendiment físic orientada a atletes i usuaris que desitgen fer un seguiment rigorós del seu entrenament i nutrició. El sistema implementa un model de subscripció per nivells (Bàsic i Élite) gestionat per un administrador, amb funcionalitats que inclouen catàleg de rutines, pla setmanal personalitzat, registre nutricional diari, historial d'entrenaments, progrés de pes i receptes. El backend es desenvolupa amb Laravel 13 i l'API REST s'assegura mitjançant Laravel Sanctum; el frontend es construeix amb Vue.js 3 (Composition API) i Tailwind CSS v4, desplegant-se el sistema en Render (backend) i Vercel (frontend).

### English

PulseFit is a fitness performance web application aimed at athletes and users who want to rigorously track their training and nutrition. The system implements a tiered subscription model (Basic and Elite) managed by an administrator, featuring routine catalogue, personalised weekly plan, daily nutrition log, workout history, weight progress tracking and recipes. The backend is built with Laravel 13, the REST API is secured with Laravel Sanctum, and the frontend is built with Vue.js 3 (Composition API) and Tailwind CSS v4. The system is deployed on Render (backend) and Vercel (frontend).

<div style="page-break-after: always;"></div>

---


## 1. INTRODUCCIÓN

### 1.1. Contexto y problemática

El mercado de las aplicaciones de fitness ha experimentado un crecimiento exponencial en los últimos años. Sin embargo, la mayoría de soluciones existentes presentan uno o varios de los siguientes problemas: elevado coste de suscripción, exceso de funcionalidades innecesarias que entorpecen la experiencia de usuario, falta de personalización real o ausencia de un sistema de gestión de contenido accesible para el administrador.

El usuario medio que desea mejorar su condición física necesita, en esencia, tres cosas: **saber qué entrenar**, **controlar lo que come** y **ver su evolución**. Las aplicaciones actuales raramente integran estos tres pilares de forma limpia y asequible.

### 1.2. Propuesta de solución

Se propone el desarrollo de **PulseFit**, una aplicación web *full-stack* que centraliza entrenamiento, nutrición y progreso físico bajo una interfaz moderna y de alto rendimiento. El sistema se articula en torno a un modelo de acceso por niveles:

- **Básico (gratuito):** acceso a rutinas de introducción, plan semanal, registro nutricional y seguimiento de peso.
- **Élite (premium):** rutinas avanzadas creadas por el administrador con mayor detalle, visualización completa de ejercicios y analíticas extendidas.
- **Administrador:** panel de control completo para gestionar usuarios, rutinas globales y solicitudes de upgrade.

### 1.3. Objetivos del proyecto

1. Desarrollar una API REST segura con Laravel Sanctum y arquitectura MVC.
2. Construir una SPA (Single Page Application) con Vue.js 3 que consuma dicha API.
3. Implementar un sistema de roles con control de acceso granular.
4. Ofrecer funcionalidades reales y conectadas a base de datos: rutinas, nutrición, progreso, historial de entrenamientos.
5. Desplegar la aplicación en servicios en la nube accesibles públicamente.

### 1.4. Alcance y limitaciones

El proyecto no incluye integración con dispositivos wearables, pagos reales ni autenticación social (OAuth). El almacenamiento de datos en producción utiliza PostgreSQL gestionado por Render.

<div style="page-break-after: always;"></div>

---

## 2. ESTADO DEL ARTE

### 2.1. Aplicaciones existentes en el mercado

| Aplicación | Puntos fuertes | Puntos débiles |
|---|---|---|
| **MyFitnessPal** | Base de datos nutricional enorme | Interfaz antigua, freemium agresivo |
| **Fitbod** | Generación IA de rutinas | Solo entrenamiento, sin nutrición |
| **Hevy** | Historial de entrenamientos detallado | Sin módulo nutricional integrado |
| **Cronometer** | Micronutrientes precisos | Sin módulo de entrenamiento |
| **Nike Training Club** | Vídeos guiados | Sin seguimiento nutricional ni de peso |

Ninguna de las soluciones analizadas combina, en una misma plataforma gratuita, rutinas con plan semanal editable, registro nutricional diario con macros, historial de entrenamientos y progreso de peso con gráfica.

### 2.2. Tecnologías analizadas

#### 2.2.1. Backend

| Framework | Lenguaje | Ventajas | Desventajas |
|---|---|---|---|
| **Laravel** | PHP | Eloquent ORM, Sanctum, ecosistema maduro | Más lento que Node en I/O intensivo |
| Express.js | Node.js | Alto rendimiento, amplio ecosistema | Sin ORM nativo, más configuración |
| Django REST | Python | Potente admin panel incluido | Curva de aprendizaje para REST puro |

Se elige **Laravel** por su integración nativa con Sanctum para autenticación de API, su ORM Eloquent que simplifica relaciones complejas y la familiaridad del equipo con PHP.

#### 2.2.2. Frontend

| Framework | Ventajas | Desventajas |
|---|---|---|
| **Vue.js 3** | Composition API, curva suave, Vite | Ecosistema más pequeño que React |
| React | Ecosistema grande, popularidad | JSX, más boilerplate |
| Angular | Completo, TypeScript nativo | Curva de aprendizaje muy pronunciada |

Se elige **Vue.js 3** por su sintaxis limpia con `<script setup>`, su integración natural con Vite y la facilidad para gestionar el estado reactivo sin librerías externas.

### 2.3. Novedades aportadas por PulseFit

- **Sistema de upgrade gestionado por administrador:** el usuario solicita el nivel Élite desde la propia app y el administrador aprueba o rechaza desde su panel, sin pasarela de pago — modelo realista para proyectos académicos o startups en fase inicial.
- **Plan semanal persistente en base de datos:** cada usuario tiene su propio plan (no localStorage), lo que permite recuperarlo desde cualquier dispositivo.
- **Cálculo de macros personalizado** mediante la fórmula Mifflin-St Jeor adaptada al objetivo del usuario (déficit, mantenimiento o superávit calórico).

<div style="page-break-after: always;"></div>

---

## 3. ESTUDIO DE VIABILIDAD

### 3.1. Análisis DAFO

|  | **Positivo** | **Negativo** |
|---|---|---|
| **Interno** | **Fortalezas:** stack moderno y bien documentado; diseño visual diferenciador; sistema de roles flexible; despliegue gratuito en Render/Vercel con PostgreSQL | **Debilidades:** sin integración wearables; generación de rutinas sin IA real; plan gratuito de Render con cold start de ~50 s |
| **Externo** | **Oportunidades:** mercado fitness en crecimiento; creciente interés por apps de salud; coste cero de despliegue en fase inicial | **Amenazas:** competencia de apps consolidadas; dependencia de planes gratuitos de Render/Vercel; cambios en políticas de planes gratuitos |

### 3.2. Estudio de mercado

El mercado global de apps de fitness fue valorado en **15.000 millones de dólares en 2023** y se espera que alcance los **33.000 millones para 2030** (Grand View Research, 2023). En España, el 34% de los usuarios de smartphone utilizan al menos una aplicación de salud o deporte semanalmente (Statista, 2024).

El público objetivo de PulseFit son personas de entre 18 y 40 años con interés en el fitness que buscan una herramienta integrada y de acceso gratuito en su nivel básico, con opción de upgrade para contenido más avanzado.

#### 3.2.1. Viabilidad técnica y económica

**Recursos hardware:**

| Recurso | Descripción | Coste |
|---|---|---|
| Portátil de desarrollo | i5/Ryzen 5, 16 GB RAM, SSD | Ya disponible |
| Servidor backend | Render (plan gratuito, Docker) | 0 € |
| Servidor frontend | Vercel (plan gratuito) | 0 € |
| Base de datos | PostgreSQL en Render (plan gratuito) | 0 € |

**Recursos software:**

| Recurso | Versión | Licencia | Coste |
|---|---|---|---|
| PHP | 8.4 | Open Source | 0 € |
| Laravel | 13.9 | MIT | 0 € |
| Vue.js | 3.x | MIT | 0 € |
| Vite | 8.x | MIT | 0 € |
| Tailwind CSS | 4.x | MIT | 0 € |
| Node.js | 20 LTS | MIT | 0 € |
| VS Code | 1.x | MIT | 0 € |

**Coste total del proyecto: 0 €** (excluido el coste del tiempo de desarrollo).

**Recursos humanos:**

| Perfil | Horas estimadas | Coste/h (mercado) | Coste estimado |
|---|---|---|---|
| Desarrollador full-stack junior | 180 h | 25 €/h | 4.500 € |

El coste sería asumible para una startup en fase inicial dado el bajo coste de infraestructura.

#### 3.2.2. Viabilidad temporal

El proyecto se desarrolla en aproximadamente **14 semanas** compaginando con las prácticas FCT, dedicando entre 10 y 15 horas semanales al desarrollo.

### 3.3. Planificación temporal — Diagrama de Gantt

```
TAREA                          FEB      MAR      ABR      MAY
                               1  2  3  4  1  2  3  4  1  2  3  4  1  2
──────────────────────────────────────────────────────────────────────────
Análisis y diseño              ████████
Migraciones y modelos BD              ████
Autenticación (Sanctum)               ████
Controladores API                        ████████
Estructura Vue + Router                       ████
Vistas principales (SPA)                          ████████████
Integración frontend-backend                               ████████
Pruebas funcionales                                               ████
Corrección de errores                                             ████
Despliegue Render + Vercel                                            ██
Memoria del proyecto                                              ████████
```

<div style="page-break-after: always;"></div>

---

## 4. ANÁLISIS DE REQUISITOS

### 4.1. Descripción de requisitos

#### 4.1.1. Requisitos funcionales

**Módulo de autenticación:**
- RF-01: El sistema permite el registro de nuevos usuarios con nombre, correo y contraseña.
- RF-02: El sistema permite el inicio y cierre de sesión mediante token Bearer (Sanctum).
- RF-03: Las rutas protegidas devuelven 401 si el token es inválido o inexistente.

**Módulo de rutinas:**
- RF-04: Los usuarios básicos visualizan rutinas de nivel básico creadas por el administrador.
- RF-05: Los usuarios premium visualizan rutinas básicas y élite con detalle completo de ejercicios.
- RF-06: Cada usuario puede asignar rutinas a los días de su plan semanal (persistente en BD).
- RF-07: El administrador puede crear y eliminar rutinas globales con ejercicios, series y repeticiones.

**Módulo nutricional:**
- RF-08: El sistema calcula las necesidades calóricas diarias mediante la fórmula Mifflin-St Jeor ajustada al objetivo del usuario.
- RF-09: El usuario puede registrar alimentos consumidos indicando nombre, calorías y macronutrientes.
- RF-10: El usuario puede añadir, editar y visualizar recetas con macros e imagen.
- RF-11: Las recetas pueden registrarse directamente en el diario nutricional.

**Módulo de progreso y entrenamientos:**
- RF-12: El usuario puede registrar su peso con fecha; el sistema muestra la evolución en una gráfica SVG.
- RF-13: El usuario puede registrar entrenamientos completados (nombre, duración, notas).
- RF-14: El dashboard muestra métricas reales: calorías hoy, entrenamientos esta semana, último peso, días planificados.

**Módulo de upgrade:**
- RF-15: El usuario básico puede solicitar el nivel Élite desde el dashboard.
- RF-16: El administrador puede aprobar o rechazar solicitudes desde su panel; al aprobar, el rol del usuario se actualiza automáticamente.

**Panel de administración:**
- RF-17: El administrador visualiza estadísticas globales: total de usuarios, suscriptores Élite, rutinas generadas.
- RF-18: El administrador puede cambiar el rol de cualquier usuario.

#### 4.1.2. Requisitos no funcionales

- RNF-01: **Seguridad** — autenticación con tokens Sanctum, validación de entradas en servidor, protección contra asignación masiva (`$fillable`).
- RNF-02: **Rendimiento** — carga inicial de la SPA inferior a 3 segundos; llamadas API paralelas con `Promise.allSettled`.
- RNF-03: **Usabilidad** — diseño responsive (mobile-first); navegación sin recarga de página.
- RNF-04: **Mantenibilidad** — arquitectura MVC en backend; Composition API en frontend con responsabilidades claras por componente.

### 4.2. Diagramas de caso de uso

#### 4.2.1. Diagrama general de actores

```
┌─────────────────────────────────────────────────────┐
│                     SISTEMA PULSEFIT                 │
│                                                     │
│  ┌──────────┐   ┌──────────────┐   ┌─────────────┐ │
│  │ Anónimo  │   │ Usuario      │   │ Admin       │ │
│  └────┬─────┘   └──────┬───────┘   └──────┬──────┘ │
│       │                │                  │        │
│   Registrarse      Ver rutinas      Crear rutinas  │
│   Iniciar sesión   Plan semanal     Gestionar users │
│                    Food log         Aprobar Élite   │
│                    Progreso peso    Ver estadísticas│
│                    Workout log                      │
│                    Solicitar Élite                  │
└─────────────────────────────────────────────────────┘
```

#### 4.2.2. Caso de uso: Solicitar upgrade a Élite

```
┌─────────────────────────────────────────────────────────┐
│         UC: Solicitar y gestionar upgrade Élite          │
│                                                         │
│  [Usuario Básico] ──► (Solicitar Élite)                 │
│                              │                          │
│                         <<include>>                     │
│                              │                          │
│                    (Comprobar solicitud previa)         │
│                                                         │
│  [Administrador] ──► (Revisar solicitudes)              │
│                         │              │                │
│                    (Aprobar)      (Rechazar)             │
│                         │                               │
│                  <<extend>>                             │
│                         │                               │
│              (Actualizar rol a premium)                 │
└─────────────────────────────────────────────────────────┘
```

#### 4.2.3. Caso de uso: Gestión del plan semanal

```
┌──────────────────────────────────────────────────────┐
│              UC: Plan Semanal                         │
│                                                      │
│  [Usuario] ──► (Ver rutinas disponibles)             │
│                         │                            │
│                    (Seleccionar rutina)               │
│                         │                            │
│              (Asignar a día de la semana) ──► BD      │
│                                                      │
│  [Usuario] ──► (Ver plan semanal actual)             │
│  [Usuario] ──► (Eliminar día del plan)               │
│                                                      │
│  Dashboard muestra automáticamente                   │
│  la rutina asignada al día actual                    │
└──────────────────────────────────────────────────────┘
```

![Plan semanal — asignación de rutinas por día](memoria_imagenes/rutinas_plan_modal.png.png)

<div style="page-break-after: always;"></div>

---

## 5. DISEÑO

### 5.1. Diseño Conceptual — Diagrama Entidad-Relación

```
┌──────────┐         ┌─────────────────┐         ┌──────────────┐
│  USERS   │ 1     N │ WEEKLY_PLAN_    │ N     1 │   ROUTINES   │
│          ├─────────┤    ITEMS        ├─────────┤              │
│ id       │         │ id              │         │ id           │
│ name     │         │ user_id (FK)    │         │ user_id (FK) │
│ email    │         │ routine_id (FK) │         │ name         │
│ password │         │ day_of_week     │         │ tier         │
│ role     │         └─────────────────┘         │ description  │
│ weight   │                                      │ training_days│
│ height   │         ┌─────────────────┐         │ is_premium   │
│ goal     │ 1     N │  UPGRADE_       │         └──────┬───────┘
└────┬─────┘ ────────┤   REQUESTS      │                │ N
     │               │ id              │                │
     │ 1             │ user_id (FK)    │                │ M
     │               │ status          │         ┌──────┴───────┐
     ├───────────────┤ message         │         │  EXERCISES   │
     │               └─────────────────┘         │              │
     │ 1                                          │ id           │
     │                                            │ name         │
     ├──────────────────────────────────────────► │ muscle_group │
     │               ┌─────────────────┐         └──────────────┘
     │  1          N │   PROGRESS      │   (tabla pivote:
     ├───────────────┤ id              │    routine_exercise
     │               │ user_id (FK)    │    con sets, reps,
     │               │ weight          │    notes)
     │               │ body_fat        │
     │               │ date            │
     │               └─────────────────┘
     │
     │  1          N ┌─────────────────┐
     ├───────────────┤   FOOD_LOGS     │
     │               │ id              │
     │               │ user_id (FK)    │
     │               │ name            │
     │               │ calories        │
     │               │ proteins        │
     │               │ carbs, fats     │
     │               │ date            │
     │               └─────────────────┘
     │
     │  1          N ┌─────────────────┐
     └───────────────┤  WORKOUT_LOGS   │
                     │ id              │
                     │ user_id (FK)    │
                     │ routine_id (FK) │
                     │ routine_name    │
                     │ duration_minutes│
                     │ logged_at       │
                     └─────────────────┘
```

La entidad **RECIPES** es independiente del usuario (son globales del sistema) y se relaciona con FOOD_LOGS de forma lógica (al añadir al registro).

### 5.2. Diseño Lógico Relacional

```
USERS(id, name, email, password, role, weight, height, goal, created_at, updated_at)

EXERCISES(id, name, muscle_group, description, created_at, updated_at)

ROUTINES(id, user_id→USERS, name, tier, description, training_days, is_premium,
         created_at, updated_at)

ROUTINE_EXERCISE(routine_id→ROUTINES, exercise_id→EXERCISES, sets, reps, notes)

WEEKLY_PLAN_ITEMS(id, user_id→USERS, routine_id→ROUTINES, day_of_week,
                  created_at, updated_at)
   CK: UNIQUE(user_id, day_of_week)

PROGRESS(id, user_id→USERS, weight, body_fat, date, created_at, updated_at)

FOOD_LOGS(id, user_id→USERS, name, calories, proteins, carbs, fats, date,
          created_at, updated_at)

RECIPES(id, name, description, calories, proteins, carbs, fats, image_url,
        is_premium, created_at, updated_at)

WORKOUT_LOGS(id, user_id→USERS, routine_id→ROUTINES, routine_name,
             duration_minutes, notes, logged_at, created_at, updated_at)

UPGRADE_REQUESTS(id, user_id→USERS, status, message, created_at, updated_at)
```

### 5.3. Diseño Físico — Descripción de tablas

#### Tabla `users`

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK, AI | Identificador único |
| name | VARCHAR(255) | NOT NULL | Nombre completo |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Correo electrónico |
| password | VARCHAR(255) | NOT NULL | Hash bcrypt |
| role | VARCHAR(20) | DEFAULT 'user' | `user`, `premium`, `admin` |
| weight | DECIMAL(5,2) | NULL | Peso en kg |
| height | INT | NULL | Altura en cm |
| goal | VARCHAR(30) | NULL | `lose_weight`, `maintain`, `gain_muscle` |

#### Tabla `routines`

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK, AI | Identificador único |
| user_id | BIGINT UNSIGNED | FK→users, NULL | NULL = rutina global de admin |
| name | VARCHAR(255) | NOT NULL | Nombre de la rutina |
| tier | VARCHAR(10) | DEFAULT 'basic' | `basic` o `elite` |
| description | TEXT | NULL | Descripción detallada |
| training_days | INT | DEFAULT 3 | Días de entrenamiento por semana |
| is_premium | BOOLEAN | DEFAULT false | Compatibilidad con filtrado |

#### Tabla `routine_exercise` (pivote)

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| routine_id | BIGINT UNSIGNED | FK→routines | Rutina asociada |
| exercise_id | BIGINT UNSIGNED | FK→exercises | Ejercicio asociado |
| sets | INT | NOT NULL | Número de series |
| reps | INT | NOT NULL | Número de repeticiones |
| notes | VARCHAR(255) | NULL | Indicaciones técnicas |

#### Tabla `weekly_plan_items`

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK, AI | Identificador único |
| user_id | BIGINT UNSIGNED | FK→users | Propietario del plan |
| routine_id | BIGINT UNSIGNED | FK→routines | Rutina asignada |
| day_of_week | TINYINT | 0–6 (Lun–Dom) | Día de la semana (lunes=0) |
|  |  | UNIQUE(user_id, day_of_week) | Un usuario, un día, una rutina |

![Tablas de la base de datos](memoria_imagenes/base_datos.png.png)

### 5.4. Orientación a objetos

#### 5.4.1. Diagrama de clases (modelos principales)

```
┌─────────────────────────────┐
│ User                        │
├─────────────────────────────┤
│ - id: int                   │
│ - name: string              │
│ - email: string             │
│ - role: string              │
│ - weight: float             │
│ - height: int               │
│ - goal: string              │
├─────────────────────────────┤
│ + routines(): HasMany       │
│ + weeklyPlan(): HasMany     │
│ + progress(): HasMany       │
│ + foodLogs(): HasMany       │
│ + workoutLogs(): HasMany    │
│ + upgradeRequests(): HasMany│
└─────────────────────────────┘
            │ 1
            │ N
┌───────────┴─────────────────┐
│ Routine                     │
├─────────────────────────────┤
│ - id: int                   │
│ - user_id: int|null         │
│ - name: string              │
│ - tier: string              │
│ - description: string       │
│ - training_days: int        │
├─────────────────────────────┤
│ + user(): BelongsTo         │
│ + exercises(): BelongsToMany│
└─────────────────────────────┘
            │ N
            │ M
┌───────────┴─────────────────┐
│ Exercise                    │
├─────────────────────────────┤
│ - id: int                   │
│ - name: string              │
│ - muscle_group: string      │
│ - description: string       │
├─────────────────────────────┤
│ + routines(): BelongsToMany │
└─────────────────────────────┘
```

#### 5.4.2. Diagrama de secuencia: Inicio de sesión

```
Usuario       Vue Login        Axios          Laravel API      Base de datos
   │                │              │                │                │
   │  submit form   │              │                │                │
   │───────────────►│              │                │                │
   │                │  POST /login │                │                │
   │                │─────────────►│                │                │
   │                │              │  validateCredentials            │
   │                │              │───────────────►│                │
   │                │              │                │ SELECT user    │
   │                │              │                │───────────────►│
   │                │              │                │◄───────────────│
   │                │              │                │ createToken()  │
   │                │              │◄───────────────│                │
   │                │  {token,user}│                │                │
   │                │◄─────────────│                │                │
   │  router.push   │              │                │                │
   │◄───────────────│              │                │                │
   │  /dashboard    │              │                │                │
```

#### 5.4.3. Diagrama de actividad: Filtrado de rutinas por rol

```
[Inicio]
    │
    ▼
¿Tiene token válido?
    │ No ──► [401 Unauthorized]
    │ Sí
    ▼
Obtener usuario autenticado
    │
    ▼
¿role = 'premium' o 'admin'?
    │ Sí ──► Devolver rutinas básicas + élite
    │ No
    ▼
Devolver solo rutinas básicas (tier='basic')
    │
    ▼
Añadir rutinas propias del usuario (user_id = id)
    │
    ▼
[Respuesta JSON]
```

### 5.5. Mapa web

```
/ (Home — solo visitantes)
├── /login
└── /register

[Autenticado]
├── /dashboard          ← Panel principal con métricas
├── /routines           ← Catálogo + plan semanal
├── /nutrition          ← Macros + recetas + food log
├── /profile            ← Perfil + BMI + objetivos
└── /admin              ← Solo administradores
    ├── Usuarios
    ├── Solicitudes Élite
    └── Gestión de rutinas
```

### 5.6. Mockups

Los mockups fueron diseñados con la herramienta **Stitch (Google)** siguiendo el sistema de diseño *Kinetic Precision*: modo oscuro, tipografía monoespaciada para métricas, verde neón (#39FF14) como color de acento y glassmorphism para tarjetas.

![Página Home — vista de escritorio](memoria_imagenes/Home.png.png)

![Dashboard principal — métricas y gráfica de peso](memoria_imagenes/dashboard.png.png)

**Usuario básico:**

![Rutinas — vista usuario básico](memoria_imagenes/rutinas_basico.png.png)

**Usuario premium:**

![Rutinas — vista usuario premium con detalles](memoria_imagenes/rutinas_premium.png.png)

![Página de Nutrición — macros y recetas](memoria_imagenes/nutricion.png.png)

![Registro Nutricional Diario](memoria_imagenes/Registro Nutricional Diario.png.png)

![Editar Receta](memoria_imagenes/Editar Receta.png.png)

<div style="page-break-after: always;"></div>

---

## 6. CODIFICACIÓN

### 6.1. Tecnologías elegidas y justificación

| Tecnología | Versión | Justificación |
|---|---|---|
| **PHP** | 8.4 | Lenguaje del ciclo; amplio soporte en hosting |
| **Laravel** | 13.9 | MVC robusto, Eloquent ORM, Sanctum incluido |
| **Laravel Sanctum** | Incluido | Autenticación SPA/API con token Bearer |
| **Vue.js 3** | 3.5 | Composition API limpia; reactivo sin Vuex |
| **Vite** | 8.0 | Bundler ultrarrápido con HMR |
| **Tailwind CSS** | 4.0 | Utilidades CSS sin CSS custom; tema por variables CSS |
| **Axios** | 1.x | Cliente HTTP con interceptores para el token Bearer |
| **Vue Router** | 4.x | SPA routing con guards de navegación |
| **PostgreSQL** | 16 | Base de datos relacional en producción (Render) |
| **SQLite** | 3.x | Base de datos local para desarrollo |
| **Docker** | — | Contenedor para despliegue del backend en Render |

### 6.2. Entorno servidor

#### 6.2.1. Arquitectura de la API REST

La API sigue el patrón **controlador–modelo–migración** de Laravel. Todos los endpoints protegidos requieren el middleware `auth:sanctum`, que verifica el token Bearer en la cabecera `Authorization`.

```
backend/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/AuthenticatedSessionController.php
│   │   ├── DashboardController.php
│   │   ├── RoutineController.php
│   │   ├── RecipeController.php
│   │   ├── FoodLogController.php
│   │   ├── WorkoutLogController.php
│   │   ├── UpgradeController.php
│   │   └── AdminController.php
│   └── Models/
│       ├── User.php
│       ├── Routine.php
│       ├── Exercise.php
│       ├── WeeklyPlanItem.php
│       ├── Progress.php
│       ├── FoodLog.php
│       ├── Recipe.php
│       ├── WorkoutLog.php
│       └── UpgradeRequest.php
├── database/migrations/
└── routes/
    ├── api.php
    └── auth.php
```

#### 6.2.2. Seguridad

**Autenticación por token:**

```php
// routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // ...todos los endpoints protegidos
});
```

El token se genera en el login y se almacena en el cliente. Cada petición lo envía en la cabecera HTTP:

```
Authorization: Bearer 1|abc123def456...
```

**Protección contra asignación masiva:** todos los modelos definen explícitamente `$fillable`, rechazando cualquier campo no declarado:

```php
// app/Models/Routine.php
protected $fillable = [
    'user_id', 'name', 'tier', 'description',
    'training_days', 'is_premium',
];
```

**Validación de entradas en servidor:**

```php
// RoutineController::store()
$request->validate([
    'name'           => 'required|string',
    'tier'           => 'required|in:basic,elite',
    'exercises.*.id' => 'required|exists:exercises,id',
    'exercises.*.sets' => 'required|integer|min:1',
]);
```

**Control de roles en controladores:**

```php
// Solo admin puede crear rutinas globales
if ($request->user()->role !== 'admin') {
    return response()->json(['message' => 'No autorizado'], 403);
}
```

#### 6.2.3. Lógica de negocio destacada

**Cálculo de macros (Mifflin-St Jeor):**

```php
// DashboardController::index()
$bmr  = (10 * $user->weight) + (6.25 * $user->height) - (5 * 25) + 5;
$tdee = $bmr * 1.55;  // Actividad moderada

if ($user->goal === 'lose_weight')    $calories -= 400;
elseif ($user->goal === 'gain_muscle') $calories += 300;
```

**Rutina del día actual según plan semanal:**

```php
$dow    = now()->dayOfWeek;           // 0=Dom..6=Sáb
$dayIdx = $dow === 0 ? 6 : $dow - 1; // Convertir a Lun=0
$todayPlan = WeeklyPlanItem::where('user_id', $user->id)
    ->where('day_of_week', $dayIdx)
    ->with('routine.exercises')
    ->first();
$routine = $todayPlan ? $todayPlan->routine : null;
```

![Código — rutas protegidas Sanctum](memoria_imagenes/codigo_sanctum_routes.png.png)

![Código — $fillable protección masiva](memoria_imagenes/codigo_fillable.png.png)

![Código Dashboard — cálculo de macros Mifflin-St Jeor](memoria_imagenes/codigo_mifflin.png.png)

![Código Dashboard — macros y métricas](memoria_imagenes/codigo_dashboard_macros.png.png)

**Relación muchos-a-muchos con pivote (error frecuente resuelto):**

Un problema encontrado durante el desarrollo fue que Eloquent genera automáticamente el nombre de la tabla pivote en orden alfabético (`exercise_routine`), pero la migración la creó como `routine_exercise`. La solución fue declarar explícitamente el nombre:

```php
// app/Models/Routine.php
public function exercises()
{
    return $this->belongsToMany(
        Exercise::class,
        'routine_exercise'    // nombre explícito
    )->withPivot('sets', 'reps', 'notes')->withTimestamps();
}
```

#### 6.2.4. Manejo de errores

Los controladores utilizan bloques `try-catch` en operaciones críticas y devuelven respuestas JSON con códigos HTTP apropiados (400, 403, 404, 500). Los errores de validación son gestionados automáticamente por Laravel devolviendo un 422 con detalle de campos.

### 6.3. Entorno cliente

#### 6.3.1. Arquitectura de la SPA

```
frontend/src/
├── views/
│   ├── Home.vue        ← Landing page pública
│   ├── Login.vue       ← Autenticación
│   ├── Register.vue    ← Registro
│   ├── Dashboard.vue   ← Panel principal
│   ├── Routines.vue    ← Catálogo y plan semanal
│   ├── Nutrition.vue   ← Macros, recetas, food log
│   ├── Profile.vue     ← Perfil y estadísticas
│   └── Admin.vue       ← Panel de administración
├── services/
│   └── api.js          ← Servicio centralizado de llamadas HTTP
├── router/
│   └── index.js        ← Rutas + guards de navegación
├── App.vue             ← Layout principal (sidebar/header)
├── style.css           ← Variables del tema + clases globales
└── main.js
```

#### 6.3.2. Sistema de autenticación en el cliente

El guard de navegación en `router/index.js` verifica el token antes de cada cambio de ruta:

```javascript
// router/index.js
router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem('auth_token')
    let user = null

    if (token) {
        const res = await axios.get('/api/user', {
            headers: { Authorization: `Bearer ${token}` }
        })
        user = res.data
    }

    if (to.meta.requiresAuth && !user)  next({ name: 'Login' })
    else if (to.meta.guestOnly && user) next({ name: 'Dashboard' })
    else if (to.meta.adminOnly && user?.role !== 'admin') next({ name: 'Dashboard' })
    else next()
})
```

#### 6.3.3. Resiliencia en llamadas paralelas

Se utiliza `Promise.allSettled` para que el fallo de un endpoint no bloquee la carga del resto de datos:

```javascript
// Dashboard.vue — onMounted
const [dashRes, foodRes, upgradeRes, logsRes] = await Promise.allSettled([
    axios.get('/api/dashboard', { headers }),
    axios.get('/api/food-log', { headers }),
    axios.get('/api/upgrade/status', { headers }),
    axios.get('/api/workout-logs', { headers }),
])

if (dashRes.status === 'fulfilled') {
    user.value        = dashRes.value.data.user
    goals.value       = dashRes.value.data.goals
    workoutStats.value = dashRes.value.data.workoutStats
}
```

#### 6.3.4. Gráfica de progreso de peso (SVG nativo)

Para visualizar la evolución del peso se implementa una gráfica de líneas con SVG puro, sin librerías externas:

```javascript
// Dashboard.vue — computed
const chartDots = computed(() => {
    const weights = progressHistory.value.map(p => parseFloat(p.weight))
    const min = Math.min(...weights) - 1
    const range = Math.max(...weights) + 1 - min
    return progressHistory.value.map((p, i) => ({
        x: (i / (progressHistory.value.length - 1)) * 300,
        y: 70 - ((parseFloat(p.weight) - min) / range) * 70,
    }))
})
```

```html
<!-- Dashboard.vue — template -->
<svg viewBox="0 0 300 70" class="w-full" style="height:70px">
    <polyline :points="chartPoints"
        fill="none" stroke="rgba(57,255,20,0.8)"
        stroke-width="2" stroke-linecap="round"/>
    <circle v-for="(pt, i) in chartDots" :key="i"
        :cx="pt.x" :cy="pt.y" r="3" fill="rgb(57,255,20)"/>
</svg>
```

![Gráfica SVG de progreso de peso](memoria_imagenes/codigo_svg_chart.png.png)

![Código — Router guard de navegación](memoria_imagenes/codigo_router_guard.png.png)

![Código — Promise.allSettled llamadas paralelas](memoria_imagenes/codigo_vue_allsettled.png.png)

#### 6.3.5. Sistema de diseño — Tailwind CSS v4

El tema utiliza variables CSS personalizadas declaradas en `style.css`:

```css
@theme {
    --color-primary-container: #39ff14;
    --color-on-primary: #000000;
    --color-surface-dim: #0d0f0c;
    --color-surface-container-lowest: #111311;
    /* ... */
}

.glass-card {
    background: rgba(255,255,255,0.03);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.06);
}

.neon-glow-btn {
    box-shadow: 0 0 15px rgba(57,255,20,0.3);
}
```

#### 6.3.6. Compatibilidad con navegadores

La aplicación ha sido probada en:

| Navegador | Versión | Resultado |
|---|---|---|
| Google Chrome | 124+ | ✅ Correcto |
| Mozilla Firefox | 125+ | ✅ Correcto |
| Microsoft Edge | 124+ | ✅ Correcto |
| Safari (iOS 17) | 17+ | ✅ Correcto |

### 6.4. Documentación interna del código

Cada controlador incluye comentarios en bloques de lógica no trivial:

```php
// DashboardController.php
// Convertir dayOfWeek de PHP (0=Dom) a nuestro índice (0=Lun)
$dow    = now()->dayOfWeek;
$dayIdx = $dow === 0 ? 6 : $dow - 1;
```

```javascript
// Routines.vue
// Promise.allSettled evita que un fallo en /user cancele la carga
// de rutinas — requisito crítico para UX resiliente
const [userRes, routinesRes] = await Promise.allSettled([...])
```

![Panel Admin — Usuarios](memoria_imagenes/admin_usuarios.png.png)

![Panel Admin — Gestión de Rutinas](memoria_imagenes/admin_rutinas.png.png)

![Panel Admin — Solicitudes Élite](memoria_imagenes/admin_solicitudes.png.png)

### 6.5. Documentación de la API

| Método | Endpoint | Auth | Descripción |
|---|---|---|---|
| POST | `/api/register` | ✗ | Registro de usuario |
| POST | `/api/login` | ✗ | Login, devuelve token |
| POST | `/api/logout` | ✓ | Revoca el token |
| GET | `/api/user` | ✓ | Datos del usuario actual |
| GET | `/api/dashboard` | ✓ | Métricas, rutina del día, historial peso |
| POST | `/api/progress` | ✓ | Registrar peso |
| GET | `/api/routines` | ✓ | Listar rutinas (filtrado por rol) |
| POST | `/api/routines` | ✓ Admin | Crear rutina global |
| DELETE | `/api/routines/{id}` | ✓ Admin | Eliminar rutina |
| GET | `/api/weekly-plan` | ✓ | Plan semanal del usuario |
| POST | `/api/weekly-plan` | ✓ | Asignar rutina a día |
| DELETE | `/api/weekly-plan/{day}` | ✓ | Eliminar día del plan |
| GET | `/api/recipes` | ✓ | Listar recetas |
| POST | `/api/recipes` | ✓ | Crear receta |
| PUT | `/api/recipes/{id}` | ✓ | Editar receta |
| GET | `/api/food-log` | ✓ | Registro nutricional de hoy |
| POST | `/api/food-log` | ✓ | Añadir entrada al registro |
| DELETE | `/api/food-log/{id}` | ✓ | Eliminar entrada |
| GET | `/api/workout-logs` | ✓ | Historial de entrenamientos |
| POST | `/api/workout-logs` | ✓ | Registrar entrenamiento |
| POST | `/api/upgrade/request` | ✓ | Solicitar upgrade a Élite |
| GET | `/api/upgrade/status` | ✓ | Estado de la solicitud |
| GET | `/api/admin/stats` | ✓ Admin | Estadísticas globales |
| GET | `/api/admin/upgrade-requests` | ✓ Admin | Listar solicitudes |
| POST | `/api/admin/upgrade-requests/{id}/approve` | ✓ Admin | Aprobar solicitud |
| POST | `/api/admin/upgrade-requests/{id}/reject` | ✓ Admin | Rechazar solicitud |
| POST | `/api/admin/users/{id}/role` | ✓ Admin | Cambiar rol de usuario |

<div style="page-break-after: always;"></div>

---

## 7. DESPLIEGUE

### 7.1. Diagrama de despliegue

```
┌─────────────────────────────────────────────────────────────────┐
│                           INTERNET                              │
└──────────────┬──────────────────────────┬───────────────────────┘
               │                          │
  ┌────────────▼────────────┐  ┌──────────▼──────────────────────┐
  │        VERCEL           │  │            RENDER                │
  │   (Frontend — Static)   │  │   (Backend — Docker Container)   │
  │                         │  │                                  │
  │  dist/ (Vue SPA)        │  │  PHP 8.4 + Laravel 13.9          │
  │  index.html + assets    │  │  Puerto 8000                     │
  │  CDN global             │  │                                  │
  │                         │  │  ┌──────────────────────────┐   │
  │  URL:                   │  │  │  PostgreSQL (Render)      │   │
  │  pulse-fit-swart.       │  │  │  pulsefit_db             │   │
  │  vercel.app             │  │  └──────────────────────────┘   │
  └─────────────────────────┘  │                                  │
             │                 │  URL: pulsefit.onrender.com      │
             │ AJAX / fetch    └──────────────────────────────────┘
             └──────────────────────────────▲
                   API REST (HTTPS + CORS)
```

### 7.2. Despliegue del backend en Render

Render no ofrece PHP como runtime nativo, por lo que el backend se despliega mediante **Docker**.

#### 7.2.1. Dockerfile

```dockerfile
FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD php artisan config:cache && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan serve --host=0.0.0.0 --port=8000
```

El CMD ejecuta en cada inicio: caché de configuración, migraciones, seeder (idempotente con `firstOrCreate`) y el servidor.

#### 7.2.2. Configuración en Render

| Campo | Valor |
|---|---|
| **Runtime** | Docker |
| **Root Directory** | `backend` |
| **Dockerfile Path** | `./Dockerfile` |
| **Plan** | Free |

#### 7.2.3. Base de datos PostgreSQL

Se crea un servicio **PostgreSQL** gratuito en Render (`pulsefit_db`) y se conecta al backend mediante variables de entorno:

```
APP_KEY=base64:...
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pulsefit.onrender.com
DB_CONNECTION=pgsql
DB_HOST=dpg-...
DB_PORT=5432
DB_DATABASE=pulsefit_db
DB_USERNAME=pulsefit_db_user
DB_PASSWORD=...
FRONTEND_URL=https://pulse-fit-swart.vercel.app
SANCTUM_STATEFUL_DOMAINS=pulse-fit-swart.vercel.app
```

> ⚠️ En el plan gratuito de Render, el servicio entra en *sleep* tras 15 minutos de inactividad. La primera petición puede tardar ~50 segundos en despertar.

![Render — Web Service PulseFit (Live)](memoria_imagenes/RenderPaginaPrin.png.png)

![Render — Events (deploys exitosos)](memoria_imagenes/RenderEvents.png.png)

![Render — Variables de entorno](memoria_imagenes/RenderEnvironment.png.png)

![Render — Logs de inicio (migraciones y seeder)](memoria_imagenes/RenderLogs.png.png)

![Render — PostgreSQL (Available)](memoria_imagenes/RenderPostgreSQL.png.png)

### 7.3. Despliegue del frontend en Vercel

**Pasos de configuración:**

1. Crear cuenta en [vercel.com](https://vercel.com) y conectar el repositorio GitHub.
2. Seleccionar la carpeta `frontend/` como raíz del proyecto.
3. Vercel detecta automáticamente Vite como framework.

| Campo | Valor |
|---|---|
| **Framework Preset** | Vite |
| **Root Directory** | `frontend` |
| **Build Command** | `npm run build` |
| **Output Directory** | `dist` |

4. Variables de entorno en Vercel:

```
VITE_API_URL=https://pulsefit.onrender.com
```

Esta variable se inyecta en tiempo de compilación mediante Vite (`import.meta.env.VITE_API_URL`) y configura la URL base de todas las llamadas Axios al backend.

![Vercel — Dashboard del proyecto](memoria_imagenes/vercelhome.png.png)

![Vercel — Deployments (historial)](memoria_imagenes/vercelDeployments.png.png)

![Vercel — Variables de entorno](memoria_imagenes/vercelenvironment.png.png)

![Vercel — Login en producción](memoria_imagenes/vercellogin.png.png)

![Vercel — Dashboard en producción](memoria_imagenes/verceldashboard.png.png)

### 7.4. Configuración de CORS en Laravel

Para permitir que el frontend en Vercel acceda a la API en Render:

```php
// config/cors.php
'paths'               => ['*'],
'allowed_origins'     => ['*'],
'allowed_methods'     => ['*'],
'allowed_headers'     => ['*'],
'supports_credentials' => false,
```

Se usa `allowed_origins = ['*']` porque Vercel genera URLs de preview dinámicas (ej. `pulse-duxsj00l5-alilek001s-projects.vercel.app`) además del dominio de producción. Al usar autenticación Bearer token (no cookies), `supports_credentials` puede ser `false`, lo que permite el wildcard de origen.

<div style="page-break-after: always;"></div>

---

## 8. HERRAMIENTAS DE APOYO

### 8.1. Control de versiones — Git y GitHub

Se utiliza **Git** como sistema de control de versiones con repositorio remoto en **GitHub**. El flujo de trabajo sigue una rama principal (`main`) con commits frecuentes y descriptivos.

![Historial de commits en GitHub — rama main](memoria_imagenes/commits.png.png)

Comandos habituales durante el desarrollo:

```bash
git add .
git commit -m "feat: add workout log endpoint and migration"
git push origin main
```

### 8.2. Entorno de desarrollo

| Herramienta | Uso |
|---|---|
| **VS Code** | Editor principal con extensiones PHP Intelephense, Volar (Vue), Tailwind CSS IntelliSense |
| **PHP Artisan** | CLI de Laravel para migraciones, seeds y gestión del servidor |
| **Composer** | Gestor de dependencias PHP |
| **npm** | Gestor de paquetes Node.js para el frontend |
| **Postman** | Pruebas manuales de la API REST |

### 8.3. Gestión de pruebas

Las pruebas realizadas son de tipo **funcional/manual** por la naturaleza del proyecto, siguiendo un plan de pruebas estructurado:

#### 8.3.1. Plan de pruebas funcionales

| ID | Caso de prueba | Resultado esperado | Estado |
|---|---|---|---|
| PT-01 | Registro con correo ya existente | Error 422 con mensaje de validación | ✅ |
| PT-02 | Login con credenciales incorrectas | Error 422 | ✅ |
| PT-03 | Acceso a `/api/dashboard` sin token | Error 401 | ✅ |
| PT-04 | Usuario básico accede a rutinas élite | Solo ve básicas | ✅ |
| PT-05 | Admin crea rutina con nivel Élite | Guardada con `tier='elite'` | ✅ |
| PT-06 | Asignar misma rutina a dos días | Segunda asignación sobreescribe (updateOrCreate) | ✅ |
| PT-07 | Registro de peso → gráfica actualizada | Nuevo punto aparece en SVG | ✅ |
| PT-08 | Solicitar upgrade ya pendiente | Error 400 con mensaje | ✅ |
| PT-09 | Admin aprueba upgrade → usuario sube a premium | `role='premium'` en BD | ✅ |
| PT-10 | Editar receta existente | PUT actualiza registro y vista | ✅ |

#### 8.3.2. Pruebas de rendimiento

Se verifica que la carga inicial del dashboard (con 4 llamadas paralelas) se complete en menos de **1,5 segundos** en entorno local. En producción con Render, el tiempo varía según el estado de *sleep* del servidor.

<div style="page-break-after: always;"></div>

---

## 9. CONCLUSIONES

### 9.1. Conclusiones sobre el trabajo realizado

Se ha desarrollado una aplicación web full-stack funcional que integra los principales conceptos del ciclo DAW: arquitectura cliente-servidor, API REST, autenticación por token, ORM, SPA, reactividad, diseño responsive y despliegue en la nube.

Los objetivos iniciales se han cumplido en su totalidad. El sistema de roles (usuario básico, premium y administrador) funciona correctamente, con control de acceso tanto en el backend (middleware y comprobaciones en controladores) como en el frontend (guards de navegación y renderizado condicional).

El principal reto técnico encontrado fue la sincronización entre el modelo relacional y el ORM: la tabla pivote `routine_exercise` requería declaración explícita en el modelo porque Eloquent la buscaba con nombre alfabético inverso. Este tipo de problema práctico demuestra la importancia de comprender el funcionamiento interno de los frameworks, no solo su API superficial.

### 9.2. Conclusiones personales

Este proyecto ha permitido consolidar los conocimientos del ciclo en un producto real y coherente. La experiencia de diseñar la base de datos desde cero, implementar una API REST segura con control de roles, conectarla con una SPA reactiva y finalmente desplegarla en la nube proporciona una visión completa del desarrollo web profesional que ningún ejercicio académico aislado podría ofrecer.

A nivel personal, ha sido especialmente valioso enfrentarse a problemas reales de producción: errores de CORS entre dominios distintos, incompatibilidades de drivers de base de datos al migrar de SQLite a PostgreSQL, y la necesidad de contenerizar la aplicación con Docker ante la ausencia de soporte PHP nativo en el proveedor elegido. Cada obstáculo ha reforzado la capacidad de diagnóstico y búsqueda de soluciones de forma autónoma.

El resultado final es una aplicación funcional, desplegada públicamente y accesible desde cualquier dispositivo, lo que convierte este proyecto en una pieza real del portfolio profesional.

### 9.3. Posibles ampliaciones y mejoras

| Mejora | Descripción | Prioridad |
|---|---|---|
| Upgrade a Render Starter | Plan gratuito tiene cold start de ~50 s; plan de pago elimina el sleep | Media |
| Autenticación social | Login con Google/GitHub para reducir fricción en registro | Media |
| Generador de rutinas con IA real | Integración con Claude API o GPT para rutinas personalizadas | Media |
| Integración con wearables | API de Fitbit/Apple Health para métricas automáticas | Baja |
| App móvil | Capacitor.js para empaquetar la SPA como app nativa | Baja |
| Notificaciones push | Recordatorios de entrenamiento y objetivos nutricionales | Baja |

<div style="page-break-after: always;"></div>

---

## 10. BIBLIOGRAFÍA

### 10.1. Documentación oficial

- **Laravel Documentation** (2026). *Laravel 13.x — Getting Started*. Recuperado de https://laravel.com/docs
- **Vue.js Documentation** (2026). *Vue 3 — Composition API*. Recuperado de https://vuejs.org/guide
- **Tailwind CSS** (2026). *Tailwind CSS v4*. Recuperado de https://tailwindcss.com/docs
- **Laravel Sanctum** (2026). *API Token Authentication*. Recuperado de https://laravel.com/docs/sanctum
- **Vite** (2026). *Vite — Next Generation Frontend Tooling*. Recuperado de https://vitejs.dev

### 10.2. Artículos y recursos

- **Mifflin, M.D. et al.** (1990). *A new predictive equation for resting energy expenditure in healthy individuals*. American Journal of Clinical Nutrition, 51(2), 241-247.
- **Grand View Research** (2023). *Fitness App Market Size Report*. Recuperado de https://www.grandviewresearch.com
- **MDN Web Docs** (2026). *SVG — Scalable Vector Graphics*. Recuperado de https://developer.mozilla.org/es/docs/Web/SVG
- **Render Documentation** (2026). *Deploying with Docker*. Recuperado de https://render.com/docs
- **Vercel Documentation** (2026). *Deploying a Vue.js Application*. Recuperado de https://vercel.com/docs

### 10.3. Herramientas utilizadas como referencia de diseño

- **Google Stitch** (2026). Herramienta de generación de interfaces de usuario con IA.

<div style="page-break-after: always;"></div>

---

## ANEXO A — ESQUEMA DE BASE DE DATOS

![Esquema completo de la base de datos](memoria_imagenes/base_datos.png.png)

Alternativamente, el esquema puede visualizarse ejecutando el comando artisan:

```bash
php artisan db:show
```

Las migraciones se encuentran en `backend/database/migrations/` e incluyen:

| Archivo | Descripción |
|---|---|
| `..._create_users_table.php` | Tabla de usuarios con roles |
| `..._create_exercises_table.php` | Catálogo de ejercicios |
| `..._create_routines_table.php` | Rutinas globales y personales |
| `..._create_routine_exercise_table.php` | Pivote rutina-ejercicio con series/reps |
| `..._create_progress_table.php` | Historial de peso |
| `..._create_recipes_table.php` | Recetas del sistema |
| `..._refactor_routines_and_plans.php` | Añade tier, weekly_plan_items, upgrade_requests |
| `..._create_food_logs_table.php` | Diario nutricional |
| `..._add_image_url_to_recipes.php` | Campo image_url en recetas |
| `..._create_workout_logs_table.php` | Historial de entrenamientos |

<div style="page-break-after: always;"></div>

---

## ANEXO B — MANUAL DE USUARIO

### Acceso a la aplicación

**URL de producción:** `https://pulse-fit-swart.vercel.app`

**Credenciales de prueba:**

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | admin@pulsefit.com | password123 |
| Usuario Élite | premium@pulsefit.com | password123 |
| Usuario Básico | user@pulsefit.com | password123 |

### Flujo básico de uso

1. **Registro:** acceder a `/register`, introducir nombre, correo y contraseña.
2. **Configurar perfil:** ir a `/profile` e indicar peso, altura y objetivo.
3. **Explorar rutinas:** ir a `/routines`, ver el catálogo y asignar rutinas al plan semanal.
4. **Dashboard:** en `/dashboard` se verá automáticamente la rutina del día actual (si se ha asignado), las métricas nutricionales y el historial de entrenamientos.
5. **Nutrición:** en `/nutrition` registrar alimentos, consultar macros diarios y gestionar recetas.
6. **Solicitar Élite:** en el dashboard, los usuarios básicos verán un banner con el botón «Solicitar Acceso Élite».

![Registro de nuevo usuario](memoria_imagenes/register.png.png)

![Inicio de sesión](memoria_imagenes/login.png.png)

![Dashboard tras el login](memoria_imagenes/dashboard.png.png)

![Perfil — datos personales y objetivos](memoria_imagenes/Perfil.png.png)

![Perfil — estadísticas y BMI](memoria_imagenes/Perfil2.png.png)

<div style="page-break-after: always;"></div>

---

## ANEXO C — MANUAL DE DESPLIEGUE LOCAL

### Requisitos previos

- PHP 8.4+
- Composer 2.x
- Node.js 20 LTS
- npm 10+

### Instalación backend

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

El servidor quedará disponible en `http://localhost:8000`.

### Instalación frontend

```bash
cd frontend
npm install
npm run dev
```

La SPA estará disponible en `http://localhost:5173`.

### Credenciales por defecto (seeder)

Si se ejecuta `--seed`, se crearán los usuarios de prueba definidos en `database/seeders/`.

---

*© 2025–2026 — Ali Lekhal Hanifi — IES Serra Perenxisa — Memoria del Proyecto DAW*
