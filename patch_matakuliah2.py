import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

td_html = """                                        </div>
                                    </td>
                                    <td>
                                        @if($mk->rps)
                                            @php
                                                $countPenelitian = $mk->rps->penelitians ? $mk->rps->penelitians->count() : 0;
                                                $countPkm = $mk->rps->pkms ? $mk->rps->pkms->count() : 0;
                                            @endphp
                                            @if($countPenelitian > 0 || $countPkm > 0)
                                                <div class="d-flex flex-column gap-1 align-items-start">
                                                @if($countPenelitian > 0)
                                                    <span class="badge bg-success" style="font-size: 0.7rem;"><i class="bi bi-journal-text me-1"></i>Penelitian ({{ $countPenelitian }})</span>
                                                @endif
                                                @if($countPkm > 0)
                                                    <span class="badge bg-info text-dark" style="font-size: 0.7rem;"><i class="bi bi-people-fill me-1"></i>PkM ({{ $countPkm }})</span>
                                                @endif
                                                </div>
                                            @else
                                                <span class="text-muted small fst-italic">-</span>
                                            @endif
                                        @else
                                            <span class="text-muted small fst-italic">Belum ada RPS</span>
                                        @endif
                                    </td>
                                    <td class="text-center d-print-none">"""

content = content.replace('                                        </div>\n                                    </td>\n                                    <td class="text-center d-print-none">', td_html)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
