import networkx as nx
import json
import matplotlib.pyplot as plt

lala = {}
with open("films.json") as f:
    lala = json.loads("".join(f.readlines()))

nodes = [node["name"] for node in lala["nodes"]]
edges = [(e['source'], e['target']) for e in lala['links']]

with open("graph.csv", "w") as f:
    for src, dest in edges:
        print ";".join([str(src), str(dest)])

"""
g = nx.Graph()
g.add_nodes_from(nodes)
g.add_edges_from(edges)
nx.draw(g)
plt.show()
"""