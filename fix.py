content = open('resources/views/layouts/app.blade.php', 'r', encoding='utf-8').read()

part1_search = """                </li>
                @if(Auth::user()->hasPermission("digital-twin"))
                <li class="nav-item mt-2">"""
part1_repl = """                </li>
                @endif
                @if(Auth::user()->hasPermission("digital-twin"))
                <li class="nav-item mt-2">"""

part2_search = """                    </a>
                </li>
                @endif
                @endif
            </ul>"""
part2_repl = """                    </a>
                </li>
                @endif
            </ul>"""

content = content.replace(part1_search, part1_repl).replace(part2_search, part2_repl)

open('resources/views/layouts/app.blade.php', 'w', encoding='utf-8').write(content)
