#map bolsonaro's followers
source("shared/users_map.r")

renderUsersMap(c('cirogomes2018', 'ciro2018', 'cirogomes', 'ciropresidente', 'cirogomespresidente'), 
                c(0, 10, 150, 300, 800), c("0 - 10", "10 - 150", "150 - 300", "300 - 800"), 'Ciro Gomes')
