<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Migrations Explained</title>
    <style>
        :root {
            --ink: #1c1a16;
            --muted: #5c564c;
            --line: #cfc6b6;
            --paper: #faf8f4;
            --code-bg: #f3f0e8;
            --accent: #0f4c5c;
            --warn-bg: #f8efe4;
            --ok-bg: #e8f2ea;
            --bad-bg: #f6e8e6;
        }

        * { box-sizing: border-box; }

        body {
            font-family: Georgia, "Times New Roman", serif;
            max-width: 860px;
            margin: 2rem auto;
            padding: 0 1rem 4rem;
            line-height: 1.55;
            color: var(--ink);
            background: var(--paper);
        }

        h1 { font-size: 1.9rem; margin: 0 0 0.4rem; }
        h2 {
            margin-top: 2.4rem;
            padding-top: 0.6rem;
            border-top: 1px solid var(--line);
            font-size: 1.35rem;
        }
        h3 { margin-top: 1.5rem; font-size: 1.1rem; }
        p { margin: 0.7rem 0; }
        ul, ol { margin: 0.5rem 0 0.9rem; padding-left: 1.3rem; }
        li { margin: 0.25rem 0; }

        a { color: var(--accent); }
        .lede { color: var(--muted); margin-bottom: 1.2rem; }

        code, pre { background: var(--code-bg); font-family: Consolas, "Courier New", monospace; }
        code { padding: 0.1rem 0.35rem; font-size: 0.92em; }
        pre {
            padding: 0.9rem 1rem;
            overflow: auto;
            font-size: 0.86rem;
            line-height: 1.45;
            border: 1px solid var(--line);
            margin: 0.8rem 0;
        }

        .toc {
            border: 1px solid var(--line);
            padding: 0.9rem 1.1rem;
            margin: 1.2rem 0 1.6rem;
            background: #fff;
        }
        .toc ol { margin: 0.4rem 0 0; }
        .toc a { text-decoration: none; }
        .toc a:hover { text-decoration: underline; }

        .box {
            border: 1px solid var(--line);
            padding: 0.85rem 1rem;
            margin: 1rem 0;
            background: #fff;
        }
        .box.warn { background: var(--warn-bg); }
        .box.ok { background: var(--ok-bg); }
        .box.bad { background: var(--bad-bg); }

        .flow {
            border: 1px solid var(--line);
            background: #fff;
            padding: 0.9rem 1.1rem;
            margin: 0.9rem 0;
            font-family: Consolas, "Courier New", monospace;
            font-size: 0.88rem;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .compare {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.9rem;
            margin: 1rem 0;
        }
        @media (max-width: 700px) {
            .compare { grid-template-columns: 1fr; }
        }
        .compare .col {
            border: 1px solid var(--line);
            background: #fff;
            padding: 0.85rem 1rem;
        }
        .compare h3 { margin-top: 0; }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0.85rem 0;
            font-size: 0.94rem;
            background: #fff;
        }
        th, td {
            border: 1px solid var(--line);
            padding: 0.45rem 0.6rem;
            text-align: left;
            vertical-align: top;
        }
        th { background: var(--code-bg); }

        .cmd {
            display: inline-block;
            background: #1c1a16;
            color: #f5f1e8;
            padding: 0.35rem 0.65rem;
            font-family: Consolas, "Courier New", monospace;
            font-size: 0.88rem;
            margin: 0.35rem 0;
        }

        .nav-top { margin-bottom: 1.2rem; font-size: 0.95rem; }
        .tag {
            display: inline-block;
            font-size: 0.75rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            border: 1px solid var(--line);
            padding: 0.1rem 0.4rem;
            margin-right: 0.35rem;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <p class="nav-top"><a href="{{ url('/') }}">← Home</a></p>

    <h1>Laravel Migrations Explained</h1>
    <p class="lede">
        All migrate commands, how they run, refresh vs fresh, and how to change one table.
        Laravel tracks executed migrations in the <code>migrations</code> table.
    </p>

    <nav class="toc" aria-label="Table of contents">
        <strong>On this page</strong>
        <ol>
            <li><a href="#commands">All migrate commands</a></li>
            <li><a href="#refresh-vs-fresh">Refresh vs Fresh</a></li>
            <li><a href="#one-table">Migrate / change one table</a></li>
            <li><a href="#workflow">Complete workflow</a></li>
            <li><a href="#most-used">Commands you'll use most</a></li>
            <li><a href="#interview">Interview tip</a></li>
        </ol>
    </nav>

    {{-- ============================================================ --}}
    <h2 id="commands">1. All migrate commands</h2>
    <p>Below are the Laravel migration commands, what they do, and when to use them.</p>

    <h3>1. Create a migration</h3>
    <p><span class="tag">file only</span> Creates a migration file — database does not change yet.</p>
    <div class="cmd">php artisan make:migration create_users_table</div>
    <p>Creates:</p>
    <pre>database/migrations/
2026_07_20_100000_create_users_table.php</pre>
    <div class="box bad">Database: nothing changes yet</div>
    <p>Use when creating a table, adding/dropping columns, or renaming tables.</p>

    <h3>2. Run pending migrations</h3>
    <div class="cmd">php artisan migrate</div>
    <p>Runs every migration that has <strong>not</strong> been executed yet. Safe for production (does not drop data).</p>
    <div class="compare">
        <div class="col">
            <h3>Before</h3>
            <pre>Database
(empty)</pre>
        </div>
        <div class="col">
            <h3>After</h3>
            <pre>users
posts
orders
migrations</pre>
        </div>
    </div>
    <p>Laravel records each run inside the <code>migrations</code> table.</p>
    <div class="flow">How it runs:
1. Scan database/migrations/
2. Skip files already in migrations table
3. Call up() on each new file
4. Insert a row into migrations</div>

    <h3>3. Check migration status</h3>
    <div class="cmd">php artisan migrate:status</div>
    <p>Shows which migrations are Ran vs Pending. Does not change the database.</p>
    <pre>+------+----------------------------------+-------+
| Ran? | Migration                        | Batch |
+------+----------------------------------+-------+
| Yes  | create_users_table               | 1     |
| Yes  | create_posts_table               | 1     |
| No   | create_orders_table              |       |
+------+----------------------------------+-------+</pre>

    <h3>4. Roll back the last batch</h3>
    <div class="cmd">php artisan migrate:rollback</div>
    <p>Undoes the <strong>last batch</strong> only (calls <code>down()</code>).</p>
    <div class="compare">
        <div class="col">
            <h3>Before rollback</h3>
            <pre>Batch 1 → users, posts
Batch 2 → categories, products, orders</pre>
        </div>
        <div class="col">
            <h3>After rollback</h3>
            <pre>users
posts

(categories, products, orders removed)</pre>
        </div>
    </div>
    <div class="flow">How it runs:
1. Find highest batch number
2. Run down() for those files (newest first)
3. Remove those rows from migrations</div>

    <h3>5. Roll back multiple steps</h3>
    <div class="cmd">php artisan migrate:rollback --step=2</div>
    <p>Rolls back the last 2 migrations (or batches depending on how they were run). Users remain if they were earlier.</p>

    <h3>6. Reset all migrations</h3>
    <div class="cmd">php artisan migrate:reset</div>
    <p>Runs every migration’s <code>down()</code>. Database becomes empty. Nothing is recreated.</p>

    <h3>7. Refresh migrations</h3>
    <div class="cmd">php artisan migrate:refresh</div>
    <p>Equivalent to <code>reset</code> then <code>migrate</code>.</p>
    <div class="flow">down() → down() → down()
↓
up() → up() → up()</div>
    <div class="box warn">All data is lost. Tables are rebuilt via each migration’s <code>down()</code> then <code>up()</code>.</div>

    <h3>8. Refresh only some steps</h3>
    <div class="cmd">php artisan migrate:refresh --step=2</div>
    <p>Refreshes only the last two batches instead of everything. Useful while developing.</p>

    <h3>9. Fresh migration</h3>
    <div class="cmd">php artisan migrate:fresh</div>
    <p>Drops <strong>all tables directly</strong> (does not call <code>down()</code>), then runs every <code>up()</code>.</p>
    <div class="flow">DROP ALL TABLES
↓
up() → up() → up()</div>
    <div class="box warn">Fastest clean rebuild. Never use on production with real data.</div>

    <h3>10. Run seeders after migrating</h3>
    <div class="cmd">php artisan migrate --seed</div>
    <p>Runs pending migrations, then <code>php artisan db:seed</code>.</p>

    <h3>11. Fresh with seed</h3>
    <div class="cmd">php artisan migrate:fresh --seed</div>
    <div class="flow">Drop all tables → Create all tables → Insert sample/default data</div>
    <p>Very common during local development.</p>

    <h3>12. Refresh with seed</h3>
    <div class="cmd">php artisan migrate:refresh --seed</div>
    <div class="flow">Rollback all → Run migrations → Run seeders</div>

    <h3>13. Run one migration only</h3>
    <div class="cmd">php artisan migrate --path=database/migrations/2026_07_20_120000_create_users_table.php</div>
    <p>Runs only that migration file (if it hasn’t already run).</p>

    <h3>14. Pretend mode</h3>
    <div class="cmd">php artisan migrate --pretend</div>
    <p>Doesn’t execute SQL — shows the SQL Laravel would run. Useful for debugging.</p>

    <h3>15. Force migration</h3>
    <div class="cmd">php artisan migrate --force</div>
    <p>Laravel blocks migrations in production by default. <code>--force</code> allows them (deployment scripts).</p>

    <h3>16. Specify database connection</h3>
    <div class="cmd">php artisan migrate --database=mysql</div>
    <p>Runs migrations on a connection defined in <code>config/database.php</code>.</p>

    <h3>17. Install migration table</h3>
    <div class="cmd">php artisan migrate:install</div>
    <p>Creates only the <code>migrations</code> tracking table. Usually automatic when you first run <code>migrate</code>.</p>

    <h3>18. Create migration for a new table</h3>
    <div class="cmd">php artisan make:migration create_products_table</div>
    <pre>Schema::create('products', function (Blueprint $table) {
    // ...
});</pre>

    <h3>19. Create migration for modifying a table</h3>
    <div class="cmd">php artisan make:migration add_phone_to_users_table --table=users</div>
    <pre>Schema::table('users', function (Blueprint $table) {
    // add / remove / rename column
});</pre>

    <h3>20. Create migration for renaming a table</h3>
    <div class="cmd">php artisan make:migration rename_products_to_items</div>
    <pre>public function up()
{
    Schema::rename('products', 'items');
}</pre>

    <h3>Quick comparison</h3>
    <table>
        <thead>
            <tr>
                <th>Command</th>
                <th>Deletes data?</th>
                <th>Re-runs migrations?</th>
                <th>Typical use</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>migrate</code></td>
                <td>No</td>
                <td>Only new ones</td>
                <td>Daily / production</td>
            </tr>
            <tr>
                <td><code>migrate:status</code></td>
                <td>No</td>
                <td>No</td>
                <td>Check progress</td>
            </tr>
            <tr>
                <td><code>migrate:rollback</code></td>
                <td>Last batch only</td>
                <td>No</td>
                <td>Undo last change</td>
            </tr>
            <tr>
                <td><code>migrate:reset</code></td>
                <td>Yes (via down)</td>
                <td>No</td>
                <td>Full undo</td>
            </tr>
            <tr>
                <td><code>migrate:refresh</code></td>
                <td>Yes</td>
                <td>Yes (all)</td>
                <td>Rebuild via down/up</td>
            </tr>
            <tr>
                <td><code>migrate:fresh</code></td>
                <td>Yes (drop all)</td>
                <td>Yes (all)</td>
                <td>Clean local DB</td>
            </tr>
            <tr>
                <td><code>migrate:install</code></td>
                <td>No</td>
                <td>No</td>
                <td>Create tracker table</td>
            </tr>
        </tbody>
    </table>

    {{-- ============================================================ --}}
    <h2 id="refresh-vs-fresh">2. Refresh vs Fresh</h2>
    <p>They look the same at the end — that’s why beginners get confused. The final schema is usually identical. The difference is <strong>how</strong> Laravel gets there.</p>

    <div class="box">
        <strong>Example migrations</strong>
        <ol>
            <li><code>create_users_table</code></li>
            <li><code>create_posts_table</code></li>
            <li><code>create_orders_table</code></li>
        </ol>
        Database has: <code>users</code>, <code>posts</code>, <code>orders</code>
    </div>

    <div class="compare">
        <div class="col">
            <h3>migrate:refresh</h3>
            <p>Laravel undoes every migration properly via <code>down()</code>.</p>
            <div class="flow">orders → down() → drop orders
posts  → down() → drop posts
users  → down() → drop users

↓

users  → up() → create users
posts  → up() → create posts
orders → up() → create orders</div>
            <p>Flow: <code>down()</code> × N, then <code>up()</code> × N.</p>
        </div>
        <div class="col">
            <h3>migrate:fresh</h3>
            <p>Laravel ignores <code>down()</code>. It drops tables directly.</p>
            <div class="flow">DROP TABLE users;
DROP TABLE posts;
DROP TABLE orders;

↓

users  → up() → create users
posts  → up() → create posts
orders → up() → create orders</div>
            <p>Flow: DROP ALL, then <code>up()</code> × N. No <code>down()</code>.</p>
        </div>
    </div>

    <h3>Why does Laravel have both?</h3>
    <pre>public function down(): void
{
    echo "Removing users table...\n";
    Schema::dropIfExists('users');
}</pre>
    <ul>
        <li><code>migrate:refresh</code> — executes this <code>down()</code>, so any logic inside it runs.</li>
        <li><code>migrate:fresh</code> — never calls <code>down()</code>; drops tables directly.</li>
    </ul>

    <h3>Which is faster?</h3>
    <p>With 200 migrations:</p>
    <table>
        <thead>
            <tr>
                <th>Command</th>
                <th>Work</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>refresh</code></td>
                <td>200× <code>down()</code> + 200× <code>up()</code> = 400 method runs</td>
            </tr>
            <tr>
                <td><code>fresh</code></td>
                <td>Drop all tables + 200× <code>up()</code> — usually faster</td>
            </tr>
        </tbody>
    </table>

    <div class="box ok">
        <strong>Interview answer</strong><br>
        <code>migrate:refresh</code> rolls back every migration by executing each <code>down()</code>, then reruns all with <code>up()</code>.
        <code>migrate:fresh</code> skips <code>down()</code>, drops all tables directly, then reruns all with <code>up()</code>.
        Final schema is usually the same; process differs; <code>fresh</code> is generally faster.
    </div>

    <table>
        <thead>
            <tr>
                <th></th>
                <th>refresh</th>
                <th>fresh</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Method</td>
                <td>Uses <code>down()</code> then <code>up()</code></td>
                <td>Drops all tables, then <code>up()</code></td>
            </tr>
            <tr>
                <td>Broken <code>down()</code></td>
                <td>Can fail</td>
                <td>Still works</td>
            </tr>
            <tr>
                <td>Extra tables</td>
                <td>May leave orphans</td>
                <td>Removes everything</td>
            </tr>
        </tbody>
    </table>

    {{-- ============================================================ --}}
    <h2 id="one-table">3. Migrate / change one table</h2>
    <p>Common interview topic: run only one of five migrations, or change only one existing table.</p>

    <h3>Case 1 — Run only one migration file</h3>
    <pre>database/migrations/
2026_07_20_100000_create_users_table.php
2026_07_20_101000_create_posts_table.php
2026_07_20_102000_create_categories_table.php
2026_07_20_103000_create_products_table.php
2026_07_20_104000_create_orders_table.php</pre>
    <p>Run only products:</p>
    <div class="cmd">php artisan migrate --path=database/migrations/2026_07_20_103000_create_products_table.php</div>
    <p>Laravel executes only that file, if it hasn’t already been run.</p>

    <h3>Case 2 — Table already migrated; change only that table</h3>
    <div class="box bad">
        <strong>Don’t edit the old migration</strong> (usually) if it has already been shared or run in production.
    </div>
    <p>Create a new migration instead:</p>
    <div class="cmd">php artisan make:migration add_phone_to_users_table --table=users</div>
    <pre>public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('phone');
    });
}</pre>
    <div class="cmd">php artisan migrate</div>
    <p>Only the <code>users</code> table changes (<code>phone</code> column added).</p>

    <h3>Case 3 — Still in early local development</h3>
    <p>If the migration hasn’t been shared / you’re okay recreating the DB, edit the original migration, then:</p>
    <div class="cmd">php artisan migrate</div>
    <p>or wipe and rebuild:</p>
    <div class="cmd">php artisan migrate:fresh</div>

    <h3>Case 4 — Last migration batch: rollback, edit, rerun</h3>
    <div class="flow">php artisan migrate:rollback
↓
Edit the migration file
↓
php artisan migrate</div>

    <h3>Example — add stock to products</h3>
    <div class="cmd">php artisan make:migration add_stock_to_products_table --table=products</div>
    <pre>public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->integer('stock')->default(0);
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('stock');
    });
}</pre>
    <div class="cmd">php artisan migrate</div>
    <p>Only the <code>products</code> table is updated.</p>

    <div class="box ok">
        <strong>Interview best practice</strong><br>
        If a migration already ran (especially production / shared), don’t edit it.
        Create a new migration with <code>Schema::table()</code>.
        Editing old migrations is OK only before they’ve been executed or in early local-only work.
    </div>

    <table>
        <thead>
            <tr>
                <th>Task</th>
                <th>Command</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Run only one migration file</td>
                <td><code>php artisan migrate --path=database/migrations/filename.php</code></td>
            </tr>
            <tr>
                <td>Add/change one table</td>
                <td><code>php artisan make:migration add_column_to_users_table --table=users</code></td>
            </tr>
            <tr>
                <td>Apply the new change</td>
                <td><code>php artisan migrate</code></td>
            </tr>
            <tr>
                <td>Rebuild whole DB (dev)</td>
                <td><code>php artisan migrate:fresh</code></td>
            </tr>
        </tbody>
    </table>

    {{-- ============================================================ --}}
    <h2 id="workflow">4. Complete workflow</h2>
    <div class="flow">Create migration
↓
php artisan make:migration
↓
Edit migration (up / down)
↓
php artisan migrate
↓
Need more changes?
↓
Create another migration
↓
php artisan migrate
↓
Need a clean database?
↓
php artisan migrate:fresh --seed</div>

    <p>In each migration file:</p>
    <ul>
        <li><code>up()</code> → runs on <code>migrate</code> / after refresh or fresh → creates or alters tables</li>
        <li><code>down()</code> → runs on <code>rollback</code> / <code>reset</code> / part of <code>refresh</code> → reverses the change</li>
    </ul>

    {{-- ============================================================ --}}
    <h2 id="most-used">5. Commands you'll use most (~80% of real projects)</h2>
    <table>
        <thead>
            <tr>
                <th>Command</th>
                <th>Purpose</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>php artisan make:migration create_users_table</code></td>
                <td>Create a migration file</td>
            </tr>
            <tr>
                <td><code>php artisan migrate</code></td>
                <td>Run pending migrations</td>
            </tr>
            <tr>
                <td><code>php artisan migrate:status</code></td>
                <td>View migration status</td>
            </tr>
            <tr>
                <td><code>php artisan migrate:rollback</code></td>
                <td>Roll back the last batch</td>
            </tr>
            <tr>
                <td><code>php artisan migrate:rollback --step=2</code></td>
                <td>Roll back the last 2 steps</td>
            </tr>
            <tr>
                <td><code>php artisan migrate:reset</code></td>
                <td>Roll back all migrations</td>
            </tr>
            <tr>
                <td><code>php artisan migrate:refresh</code></td>
                <td>Reset all and rerun</td>
            </tr>
            <tr>
                <td><code>php artisan migrate:fresh</code></td>
                <td>Drop all tables and rerun</td>
            </tr>
            <tr>
                <td><code>php artisan migrate:fresh --seed</code></td>
                <td>Fresh DB + seed data</td>
            </tr>
            <tr>
                <td><code>php artisan migrate:refresh --seed</code></td>
                <td>Refresh DB + seed data</td>
            </tr>
            <tr>
                <td><code>php artisan migrate --path=...</code></td>
                <td>Run one specific migration</td>
            </tr>
            <tr>
                <td><code>php artisan migrate --pretend</code></td>
                <td>Show SQL without executing</td>
            </tr>
            <tr>
                <td><code>php artisan migrate --force</code></td>
                <td>Force migrations in production</td>
            </tr>
            <tr>
                <td><code>php artisan migrate --database=mysql</code></td>
                <td>Run on a specific connection</td>
            </tr>
            <tr>
                <td><code>make:migration ... --table=users</code></td>
                <td>Modify an existing table</td>
            </tr>
        </tbody>
    </table>

    {{-- ============================================================ --}}
    <h2 id="interview">6. Interview tip</h2>
    <div class="box">
        Know these thoroughly:
        <ul>
            <li><code>make:migration</code></li>
            <li><code>migrate</code></li>
            <li><code>migrate:status</code></li>
            <li><code>migrate:rollback</code></li>
            <li><code>migrate:reset</code></li>
            <li><code>migrate:refresh</code></li>
            <li><code>migrate:fresh</code></li>
            <li><code>migrate:fresh --seed</code></li>
            <li><code>migrate --path</code></li>
            <li><code>make:migration ... --table=users</code></li>
        </ul>
        These cover most Laravel migration interview questions.
    </div>

    <p class="lede" style="margin-top: 2rem;">
        Open this page at <code>{{ url('/migration') }}</code>
    </p>
</body>
</html>
